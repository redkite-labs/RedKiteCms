# ThemeEngineBundle

ThemeEngineBundle is the bundle deputated to render a website created by RedKite CMS,
both during the developing and then in production.

Although an application created by RedKite CMS does not require the CMS itself to
work in production environment, you need this bundle to render the twig files generated 
by RedKite to render the website in production.

[![Build Status](https://secure.travis-ci.org/alphalemon/ThemeEngineBundle.png)](http://travis-ci.org/alphalemon/ThemeEngineBundle)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/redkite-labs/ThemeEngineBundle/badges/quality-score.png?s=934279aec511fe6e7a632af196533fbbfc7b6dbd)](https://scrutinizer-ci.com/g/redkite-labs/ThemeEngineBundle/)
[![Code Coverage](https://scrutinizer-ci.com/g/redkite-labs/ThemeEngineBundle/badges/coverage.png?s=55eabb27d3e1c0229bba357f870fd5ff6d571d5d)](https://scrutinizer-ci.com/g/redkite-labs/ThemeEngineBundle/)

## Configuration
This bundle exposes some properties you can configure to adapt some bundle behaviors:

- deploy_bundle
- base_template
- stage_templates_folder
- templates_folder
- bootstrap

### The deploy_bundle parameter
This parameter defines the bundle where RedKite CMS will deploy the generated files 
for the website pages.

    red_kite_labs_theme_engine:
        deploy_bundle: AcmeWebSiteBundle

### The base_template parameter
This parameter defines the base template the bundle will use to render each website
page:

    red_kite_labs_theme_engine:
        base_template: AcmeWebSiteBundle:Theme:base.html.twig

### The stage_templates_folder parameter
This parameter defines the folder for the stage environment where RedKite CMS will 
deploy the generated files for the website pages into the deploy bundle:

    red_kite_labs_theme_engine:
        stage_templates_folder: StageFolder

### The templates_folder parameter
This parameter defines the folder for the production environment where RedKite CMS will 
deploy the generated files for the website pages into the deploy bundle:

    red_kite_labs_theme_engine:
        templates_folder: ProdFolder

### The bootstrap parameter
This parameter defines the bootstrap version required by a theme:

    red_kite_labs_theme_engine:
        bootstrap:
          theme: [{theme: BootbusinessThemeBundle, version: 2.x}]