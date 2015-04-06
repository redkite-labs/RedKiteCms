#RedKite CMS base layout
RedKite CMS implements a very flexible layout as base template used to render the whole pages of the website:

    <!DOCTYPE html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>{% block title %}{% if metatitle is defined %}{{ metatitle }}{% endif %}{% endblock %}</title>
            <meta name="Description" content="{% block description %}{% if metadescription is defined %}{{ metadescription }}{% endif %}{% endblock %}" />
            <meta name="Keywords" content="{% block keywords %}{% if metakeywords is defined %}{{ metakeywords }}{% endif %}{% endblock %}" />
            <meta name="generator" content="RedKite CMS" />
            {% block metatags %}        
            {% endblock %}

            {% block assets %}        
                {% block pre_external_stylesheets %}
                {% endblock %}

                {% block external_stylesheets %}
                {% endblock %}

                {% block post_external_stylesheets %}
                {% endblock %}

                {% block pre_external_javascripts %}
                {% endblock %}

                {% block external_javascripts %}
                {% endblock %}

                {% block post_external_javascripts %}
                {% endblock %}
        
                {% block internal_header_javascripts %}{% endblock %}
                {% block internal_header_stylesheets %}{% endblock %}

                {% block conditional_assets %}
                {% endblock %}
            {% endblock %}

            <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
        </head>

        {% block body_tag %}<body>{% endblock %}
            {% block body %}{% endblock %}
            {% block internal_body_javascripts %}{% endblock %}
        </body>    
    </html>

Let's analyse it in detail.
	
## Metatags
In addiction to standard meta-tags entries, title, description and keywords, there is a twig block called **metatags** where RedKite CMS places additional meta-tags that could come from a custom block.

When your template needs to add extra meta-tags, just override this block and add your own.

    {% block metatags %}
    {% endblock %}

## Assets
There are several blocks available to add each kind of asset to the webpage: 

    {% block assets %}        
        {% block pre_external_stylesheets %}
        {% endblock %}

        {% block external_stylesheets %}
        {% endblock %}

        {% block post_external_stylesheets %}
        {% endblock %}

        {% block pre_external_javascripts %}
        {% endblock %}

        {% block external_javascripts %}
        {% endblock %}

        {% block post_external_javascripts %}
        {% endblock %}
        
        {% block internal_header_javascripts %}{% endblock %}
        {% block internal_header_stylesheets %}{% endblock %}

        {% block conditional_assets %}
        {% endblock %}
    {% endblock %}

There is a main **"assets"** block which contains all the assets blocks sections.

### External assets
There are two main blocks defined inside the assets block, for external assets: 

- external_stylesheets
- external_javascripts

and both of them have a **pre** and **post** block.

That separation has been implemented because assets position is important to properly render the page, especially for stylesheets. For example if you need to use a **reset** external stylesheet which must reset all html elements, it must be place at top of  stylesheets list, so, looking at our assets section, it probably will be placed at the first place of the **pre_external_stylesheets** block.

When you design you theme, you must be aware that RedKite CMS completely overrides the two main blocks, so **external_stylesheets** and **external_javascripts**, because it uses those sections to render the assets it requires, which are:

- Twitter Bootstrap
- Jquery
- Jquery-ui
- Jquery.easing
- Holder
- Elfinder

It is very important to avoid to declare an asset two times on the same page, because it can produce undesired effects, that could compromise the CMS functionalities.

This is the reason why both main sections are overriden and those are the section you must use, when your website requires one or more of the assets already required by 
RedKite CMS.


### Internal assets
Somethimes you may need to add internal javascript code or stylesheet rules directly on the webpage, instead of declaring them into an external file.

RedKite CMS defines the blocks described below, for this evenience:

    {% block internal_header_javascripts %}{% endblock %}
    {% block internal_header_stylesheets %}{% endblock %}

When you need to add a javascript code at the end of the **body** tag instead of inside the **header**, you can use the following block:

    {% block internal_body_javascripts %}{% endblock %}

which renders the code under the page contents.

### Conditional assets

When you need to add conditional assets to your website you can use the **conditional_assets** block

    {% block conditional_assets %}
    {% endblock %}

## The body contents
The contents that must live inside the **body** tag must be added to the block **body**

    {% block body %}
    {% endblock %}

Sometimes websites adds classes to the **body** tag, so the **body_tag** block can be overriden to change the body declaration:

    {% block body_tag %}<body>{% endblock %}
    
Found a typo ? Something is wrong in this documentation ? [Just fork and edit it !](https://github.com/redkite-labs/RedKiteCms/edit/master/docs/contribute/redkite-cms-base-layout.md)