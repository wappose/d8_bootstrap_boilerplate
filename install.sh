#!/bin/bash

pname=${PWD##*/}

cp -r www/themes/boilerplate www/themes/$pname
mv www/themes/$pname/boilerplate.info.yml www/themes/$pname/$pname.info.yml
mv www/themes/$pname/boilerplate.libraries.yml www/themes/$pname/$pname.libraries.yml
mv www/themes/$pname/boilerplate.theme www/themes/$pname/$pname.theme
mv www/themes/$pname/config/install/boilerplate.settings.yml www/themes/$pname/config/install/$pname.settings.yml
mv www/themes/$pname/config/schema/boilerplate.schema.yml www/themes/$pname/config/schema/$pname.schema.yml
mv src/stylesheets/boilerplate src/stylesheets/$pname

sed -i "s/boilerplate/$pname/" www/themes/$pname/$pname.info.yml
pname2=${pname^}
sed -i "s/Boilerplate/$pname2/" www/themes/$pname/$pname.info.yml
sed -i "s/boilerplate/$pname/" www/themes/$pname/$pname.theme
sed -i "s/boilerplate/$pname/" www/themes/$pname/config/schema/$pname.schema.yml
sed -i "s/boilerplate/$pname/" src/stylesheets/style.scss
sed -i "s/boilerplate/$pname/" sasswatch.sh

rm -- "$0"