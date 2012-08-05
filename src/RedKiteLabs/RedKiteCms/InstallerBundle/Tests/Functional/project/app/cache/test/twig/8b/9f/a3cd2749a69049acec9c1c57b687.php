<?php

/* AlphaLemonCmsInstallerBundle:Installer:base.html.twig */
class __TwigTemplate_8b9fa3cd2749a69049acec9c1c57b687 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'external_stylesheets' => array($this, 'block_external_stylesheets'),
            'external_javascripts' => array($this, 'block_external_javascripts'),
            'internal_header_javascripts' => array($this, 'block_internal_header_javascripts'),
            'internal_header_stylesheets' => array($this, 'block_internal_header_stylesheets'),
            'conditional_assets' => array($this, 'block_conditional_assets'),
            'body_tag' => array($this, 'block_body_tag'),
            'body' => array($this, 'block_body'),
            'internal_body_javascripts' => array($this, 'block_internal_body_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 15
        echo "
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
        <title>";
        // line 20
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        <meta name=\"Description\" content=\"";
        // line 21
        if (array_key_exists("metadescription", $context)) {
            echo twig_escape_filter($this->env, $this->getContext($context, "metadescription"), "html", null, true);
        }
        echo "\" />
        <meta name=\"Keywords\" content=\"";
        // line 22
        if (array_key_exists("metakeywords", $context)) {
            echo twig_escape_filter($this->env, $this->getContext($context, "metakeywords"), "html", null, true);
        }
        echo "\" />
        <meta name=\"generator\" content=\"AlphaLemon CMS\" />
        
        ";
        // line 25
        $this->displayBlock('external_stylesheets', $context, $blocks);
        // line 30
        echo "        ";
        $this->displayBlock('external_javascripts', $context, $blocks);
        // line 35
        echo "        
        ";
        // line 36
        $this->displayBlock('internal_header_javascripts', $context, $blocks);
        // line 37
        echo "        ";
        $this->displayBlock('internal_header_stylesheets', $context, $blocks);
        // line 38
        echo "        
        ";
        // line 39
        $this->displayBlock('conditional_assets', $context, $blocks);
        // line 41
        echo "        <link rel=\"shortcut icon\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" />
    </head>
    
    ";
        // line 44
        $this->displayBlock('body_tag', $context, $blocks);
        // line 45
        echo "        ";
        $this->displayBlock('body', $context, $blocks);
        // line 46
        echo "        ";
        $this->displayBlock('internal_body_javascripts', $context, $blocks);
        // line 47
        echo "    </body>
    
</html>
";
    }

    // line 20
    public function block_title($context, array $blocks = array())
    {
        if (array_key_exists("metatitle", $context)) {
            echo twig_escape_filter($this->env, $this->getContext($context, "metatitle"), "html", null, true);
        }
    }

    // line 25
    public function block_external_stylesheets($context, array $blocks = array())
    {
        // line 26
        echo "            ";
        if ((array_key_exists("stylesheets_template", $context) && ($this->getContext($context, "stylesheets_template") != ""))) {
            // line 27
            echo "                ";
            $template = $this->env->resolveTemplate($this->getContext($context, "stylesheets_template"));
            $template->display($context);
            // line 28
            echo "            ";
        }
        // line 29
        echo "        ";
    }

    // line 30
    public function block_external_javascripts($context, array $blocks = array())
    {
        // line 31
        echo "            ";
        if ((array_key_exists("javascripts_template", $context) && ($this->getContext($context, "javascripts_template") != ""))) {
            // line 32
            echo "                ";
            $template = $this->env->resolveTemplate($this->getContext($context, "javascripts_template"));
            $template->display($context);
            // line 33
            echo "            ";
        }
        // line 34
        echo "        ";
    }

    // line 36
    public function block_internal_header_javascripts($context, array $blocks = array())
    {
    }

    // line 37
    public function block_internal_header_stylesheets($context, array $blocks = array())
    {
    }

    // line 39
    public function block_conditional_assets($context, array $blocks = array())
    {
        // line 40
        echo "        ";
    }

    // line 44
    public function block_body_tag($context, array $blocks = array())
    {
        echo "<body>";
    }

    // line 45
    public function block_body($context, array $blocks = array())
    {
    }

    // line 46
    public function block_internal_body_javascripts($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "AlphaLemonCmsInstallerBundle:Installer:base.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  161 => 46,  156 => 45,  150 => 44,  146 => 40,  143 => 39,  133 => 36,  129 => 34,  126 => 33,  122 => 32,  119 => 31,  116 => 30,  112 => 29,  109 => 28,  105 => 27,  102 => 26,  91 => 20,  84 => 47,  81 => 46,  78 => 45,  76 => 44,  69 => 41,  67 => 39,  64 => 38,  61 => 37,  59 => 36,  56 => 35,  53 => 30,  43 => 22,  37 => 21,  33 => 20,  191 => 127,  185 => 124,  177 => 119,  173 => 118,  167 => 115,  163 => 114,  159 => 113,  152 => 109,  138 => 37,  132 => 95,  120 => 85,  114 => 82,  110 => 80,  108 => 79,  104 => 78,  99 => 25,  96 => 74,  54 => 34,  51 => 25,  44 => 27,  41 => 26,  34 => 23,  31 => 19,  26 => 15,);
    }
}
