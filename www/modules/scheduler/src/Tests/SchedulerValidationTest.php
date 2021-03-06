<?php

namespace Drupal\scheduler\Tests;

use Drupal\Component\Utility\SafeMarkup;

/**
 * Tests the validation when editing a node.
 *
 * @group scheduler
 */
class SchedulerValidationTest extends SchedulerTestBase {

  /**
   * Tests the validation when editing a node.
   *
   * The 'required' checks and 'dates in the past' checks are handled in other
   * tests. This test checks validation when fields interact.
   */
  public function testValidationDuringEdit() {
    $this->drupalLogin($this->adminUser);

    // Set unpublishing to be required.
    $this->nodetype->setThirdPartySetting('scheduler', 'unpublish_required', TRUE)->save();
    $type = $this->nodetype->get('type');

    // Create an unpublished page node, then edit the node and check that if a
    // publish-on date is entered then an unpublish-on date is also needed.
    $node = $this->drupalCreateNode(['type' => $type, 'status' => FALSE]);
    $edit = [
      'publish_on[0][value][date]' => date('Y-m-d', strtotime('+1 day', REQUEST_TIME)),
      'publish_on[0][value][time]' => date('H:i:s', strtotime('+1 day', REQUEST_TIME)),
    ];
    $this->drupalPostForm('node/' . $node->id() . '/edit', $edit, t('Save and keep unpublished'));
    $this->assertRaw(t("If you set a 'publish on' date then you must also set an 'unpublish on' date."), 'Validation prevents entering a publish-on date with no unpublish-on date if unpublishing is required.');
    $this->assertNoRaw(t('@type %title has been updated.', ['@type' => $this->nodetype->get('name'), '%title' => SafeMarkup::checkPlain($node->title->value)]), 'The node has not been saved.');


    // Create an unpublished page node, then edit the node and check that if the
    // status is changed to published, then an unpublish-on date is also needed.
    $node = $this->drupalCreateNode(['type' => $type, 'status' => FALSE]);
    $this->drupalPostForm('node/' . $node->id() . '/edit', [], t('Save and publish'));
    $this->assertRaw(t("Either you must set an 'unpublish on' date or save this node as unpublished."), 'Validation prevents publishing the node directly without an unpublish-on date if unpublishing is required.');
    $this->assertNoRaw(t('@type %title has been updated.', ['@type' => $this->nodetype->get('name'), '%title' => SafeMarkup::checkPlain($node->title->value)]), 'The node has not been saved.');

    // Create an unpublished page node, edit the node and check that if both
    // dates are entered then the unpublish date is later than the publish date.
    $node = $this->drupalCreateNode(['type' => $type, 'status' => FALSE]);
    $edit = [
      'publish_on[0][value][date]' => \Drupal::service('date.formatter')->format(REQUEST_TIME + 7200, 'custom', 'Y-m-d'),
      'publish_on[0][value][time]' => \Drupal::service('date.formatter')->format(REQUEST_TIME + 7200, 'custom', 'H:i:s'),
      'unpublish_on[0][value][date]' => \Drupal::service('date.formatter')->format(REQUEST_TIME + 3600, 'custom', 'Y-m-d'),
      'unpublish_on[0][value][time]' => \Drupal::service('date.formatter')->format(REQUEST_TIME + 3600, 'custom', 'H:i:s'),
    ];
    $this->drupalPostForm('node/' . $node->id() . '/edit', $edit, t('Save and keep unpublished'));
    $this->assertRaw(t("The 'unpublish on' date must be later than the 'publish on' date."), 'Validation prevents entering an unpublish-on date which is earlier than the publish-on date.');
    $this->assertNoRaw(t('@type %title has been updated.', ['@type' => $this->nodetype->get('name'), '%title' => SafeMarkup::checkPlain($node->title->value)]), 'The node has not been saved.');
  }
}
