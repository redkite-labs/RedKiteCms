<?php

/* AlphaLemonCmsInstallerBundle:Installer:install.html.twig */
class __TwigTemplate_d65562e40e7599a3ec213571ec0b6408 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("AlphaLemonCmsInstallerBundle:Installer:base.html.twig");

        $this->blocks = array(
            'external_stylesheets' => array($this, 'block_external_stylesheets'),
            'external_javascripts' => array($this, 'block_external_javascripts'),
            'internal_header_stylesheets' => array($this, 'block_internal_header_stylesheets'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "AlphaLemonCmsInstallerBundle:Installer:base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 17
        $this->env->getExtension('form')->renderer->setTheme($this->getContext($context, "form"), array(0 => "AlphaLemonCmsInstallerBundle::Installer/form.html.twig"));
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 19
    public function block_external_stylesheets($context, array $blocks = array())
    {
        // line 23
        echo "<link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/alphalemoncmsinstaller/css/style.css"), "html", null, true);
        echo "\" rel=\"stylesheet\" type=\"text/css\" />
";
    }

    // line 26
    public function block_external_javascripts($context, array $blocks = array())
    {
        // line 27
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("bundles/alphalemoncmsinstaller/js/jquery-last.min.js"), "html", null, true);
        echo "\" rel=\"stylesheet\" type=\"text/css\" />
    ";
    }

    // line 33
    public function block_internal_header_stylesheets($context, array $blocks = array())
    {
        // line 34
        echo "    <script>
    \$(document).ready(function(){ 
        
        setByDriver(\$('#alphalemon_cms_parameters_driver').val());
        \$('#alphalemon_cms_parameters_driver').change(function(){            
            
            setByDriver(\$(this).val());
            return false;
        });
        
        function setByDriver(driver) {
            switch(driver) {
                case 'mysql':
                    showStandardDbParams();
                    \$('#alphalemon_cms_parameters_port').val('3306');
                    break;
                case 'pgsql':
                    showStandardDbParams();
                    \$('#alphalemon_cms_parameters_port').val('5432');
                    break;
                default:
                    showDsnDbParams();
                    break;
            }
        }

        function showDsnDbParams() {
            \$('#al_db_standard_section').hide();
            \$('#al_db_dsn_section').show();
        }
        
        function showStandardDbParams() {
            \$('#alphalemon_cms_parameters_dsn').val('');
            \$('#al_db_standard_section').show();
            \$('#al_db_dsn_section').hide();
        }
    });
    </script>
";
    }

    // line 74
    public function block_body($context, array $blocks = array())
    {
        // line 75
        echo "<div id=\"al_main\">
<h1>AlphaLemonCMS Web-Installer interface</h1>
<div id=\"al_installer_box\">
    <form action=\"";
        // line 78
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_install"), "html", null, true);
        echo "\" method=\"POST\">
        ";
        // line 79
        if ($this->getAttribute($this->getAttribute($this->getContext($context, "app"), "session"), "hasFlash", array(0 => "error"), "method")) {
            // line 80
            echo "        <div id=\"al_process_errors\">
            <h2>Ops. Something was wrong</h2>
            <p>";
            // line 82
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, "app"), "session"), "flash", array(0 => "error"), "method"), "html", null, true);
            echo "</p>
        </div>
        ";
        }
        // line 85
        echo "        <div>
            <h2>Bundle data</h2>
            <p>
            AlphaLemon CMS requires a bundle where AlphaLemon CMS will save the contents you insert. Every time
            you start a new Symfony2 project you always create a new bundle where your application lives:
            the required external bundle, by AlphaLemon CMS, is exactly that bundle.
            </p>
        </div>
        <div id=\"al_bundle_section\">
            <div class=\"al_column\">
            ";
        // line 95
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "company"), 'row');
        echo "
            </div>
            <div class=\"al_column\">
            ";
        // line 98
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "bundle"), 'row');
        echo "
            </div>
            <div class=\"al_clear\"></div>          
        </div>
        <div>
            <h2>Database</h2>
            <p>
            Please provide the information required to access your database
            </p>
        </div>
        <div>
            ";
        // line 109
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "driver"), 'row');
        echo "
        </div>
        <div id=\"al_db_standard_section\">
            <div class=\"al_column\">
            ";
        // line 113
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "host"), 'row');
        echo "
            ";
        // line 114
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "database"), 'row');
        echo "
            ";
        // line 115
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "port"), 'row');
        echo "
            </div>
            <div class=\"al_column\">
            ";
        // line 118
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "user"), 'row');
        echo "
            ";
        // line 119
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "password"), 'row');
        echo "
            </div>
            <div class=\"al_clear\"></div>
        </div>
        <div id=\"al_db_dsn_section\" style=\"display:none\">
            ";
        // line 124
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute($this->getContext($context, "form"), "dsn"), 'row');
        echo "
        </div>
        <div id=\"al_dsn_section\">
            ";
        // line 127
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'rest');
        echo "
        </div>
        <div>
            <p>* Required field</p>
            <input type=\"submit\" value=\"install\" />
        </div>
    </form>
</div>
</div>
";
    }

    public function getTemplateName()
    {
        return "AlphaLemonCmsInstallerBundle:Installer:install.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  191 => 127,  185 => 124,  177 => 119,  173 => 118,  167 => 115,  163 => 114,  159 => 113,  152 => 109,  138 => 98,  132 => 95,  120 => 85,  114 => 82,  110 => 80,  108 => 79,  104 => 78,  99 => 75,  96 => 74,  54 => 34,  51 => 33,  44 => 27,  41 => 26,  34 => 23,  31 => 19,  26 => 17,);
    }
}
