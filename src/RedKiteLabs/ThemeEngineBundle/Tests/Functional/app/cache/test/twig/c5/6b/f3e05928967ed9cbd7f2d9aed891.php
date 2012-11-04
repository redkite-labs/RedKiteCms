<?php

/* AlphaLemonThemeEngineBundle:Themes:theme_skeleton.html.twig */
class __TwigTemplate_c56bf3e05928967ed9cbd7f2d9aed891 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'theme_commands' => array($this, 'block_theme_commands'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 15
        echo "
";
        // line 16
        $context["domain"] = "al_themes";
        // line 17
        echo "
<h2>";
        // line 18
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "theme_values"), "theme_title"), "html", null, true);
        echo "</h2>

<table>
";
        // line 21
        if ($this->getAttribute((isset($context["theme_values"]) ? $context["theme_values"] : null), "info", array(), "any", true, true)) {
            // line 22
            echo "<tr><td rowspan=\"";
            echo twig_escape_filter($this->env, (twig_length_filter($this->env, $this->getAttribute($this->getContext($context, "theme_values"), "info")) + 1), "html", null, true);
            echo "\" width=\"120\"><img src=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "theme_values"), "screenshot"), "html", null, true);
            echo "\" width=\"100\" height=\"100\" /></td></tr>
";
            // line 23
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "theme_values"), "info"));
            foreach ($context['_seq'] as $context["key"] => $context["value"]) {
                // line 24
                echo "<tr><th>";
                echo twig_escape_filter($this->env, $this->getContext($context, "key"), "html", null, true);
                echo "</th><td>";
                echo twig_escape_filter($this->env, $this->getContext($context, "value"), "html", null, true);
                echo "</td></tr>
";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['key'], $context['value'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
        } else {
            // line 27
            echo "  ";
            if ($this->getAttribute((isset($context["theme_values"]) ? $context["theme_values"] : null), "theme_error", array(), "any", true, true)) {
                // line 28
                echo "  <tr><td colspan=\"2\">";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "theme_values"), "theme_error"), "html", null, true);
                echo "</td></tr>
  ";
            } else {
                // line 30
                echo "  <tr><td colspan=\"2\"><b>";
                echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("Any information available for this theme", array(), $this->getContext($context, "domain")), "html", null, true);
                echo "</b></td></tr>
  ";
            }
        }
        // line 33
        echo "
  <tr>
    <td colspan=\"3\">
        ";
        // line 36
        $this->displayBlock('theme_commands', $context, $blocks);
        // line 45
        echo "    </td>
  </tr>
</table>";
    }

    // line 36
    public function block_theme_commands($context, array $blocks = array())
    {
        // line 37
        echo "        <table>
            <tr>
              ";
        // line 39
        if ($this->getAttribute((isset($context["theme_values"]) ? $context["theme_values"] : null), "buttons", array(), "any", true, true)) {
            // line 40
            echo "                  <td><a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_activate_theme", array("themeName" => $this->getAttribute($this->getContext($context, "theme_values"), "theme_title"))), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("Activate", array(), $this->getContext($context, "domain")), "html", null, true);
            echo "</a></td>
              ";
        }
        // line 42
        echo "            </tr>
        </table>
        ";
    }

    public function getTemplateName()
    {
        return "AlphaLemonThemeEngineBundle:Themes:theme_skeleton.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  95 => 40,  93 => 39,  89 => 37,  86 => 36,  80 => 45,  78 => 36,  73 => 33,  66 => 30,  60 => 28,  32 => 21,  23 => 17,  18 => 15,  97 => 40,  74 => 35,  53 => 32,  45 => 24,  38 => 24,  31 => 21,  28 => 20,  22 => 17,  20 => 16,  17 => 15,  170 => 44,  165 => 43,  160 => 39,  155 => 38,  151 => 37,  148 => 36,  139 => 34,  134 => 33,  132 => 32,  127 => 31,  123 => 30,  120 => 29,  111 => 27,  106 => 26,  103 => 42,  100 => 24,  92 => 37,  84 => 21,  76 => 20,  70 => 45,  68 => 44,  64 => 43,  57 => 27,  54 => 39,  51 => 31,  48 => 31,  46 => 24,  41 => 23,  33 => 20,  37 => 21,  34 => 22,  29 => 21,  26 => 18,  21 => 16,);
    }
}
