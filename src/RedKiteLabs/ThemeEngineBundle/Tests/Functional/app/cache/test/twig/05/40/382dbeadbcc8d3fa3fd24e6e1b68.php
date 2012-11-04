<?php

/* AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig */
class __TwigTemplate_0540382dbeadbcc8d3fa3fd24e6e1b68 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
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
<td valign=\"top\" width=\"50%\">
    ";
        // line 19
        if ($this->getAttribute((isset($context["values"]) ? $context["values"] : null), "active_theme", array(), "any", true, true)) {
            // line 20
            echo "    <div id=\"al_active_theme_information\">
    ";
            // line 21
            $template = $this->env->resolveTemplate($this->getContext($context, "theme_skeleton"));
            $template->display(array_merge($context, array("theme_values" => $this->getAttribute($this->getContext($context, "values"), "active_theme"))));
            // line 22
            echo "    </div>
    ";
        } else {
            // line 24
            echo "        <p><b>";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("Any active theme has been define", array(), $this->getContext($context, "domain")), "html", null, true);
            echo "</b></p>
        <p>Choose the active theme from the right panel</p>
    ";
        }
        // line 27
        echo "</td>

<td valign=\"top\" width=\"50%\">
    <div class=\"al_theme_panel_section\">
        ";
        // line 31
        if (($this->getAttribute((isset($context["values"]) ? $context["values"] : null), "available_themes", array(), "any", true, true) && (twig_length_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, "values"), "available_themes"), "themes")) > 0))) {
            // line 32
            echo "        <div id=\"al_available_themes_information\">
        <table >
            ";
            // line 34
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getAttribute($this->getContext($context, "values"), "available_themes"), "themes"));
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 35
                echo "            <tr><td valign=\"top\">";
                $template = $this->env->resolveTemplate($this->getContext($context, "theme_skeleton"));
                $template->display(array_merge($context, array("theme_values" => $this->getContext($context, "item"))));
                echo "</td></tr>
            ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 37
            echo "        </table>
        </div>
        ";
        } else {
            // line 40
            echo "          <p><b>";
            echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans("Any other theme is installed at the moment", array(), $this->getContext($context, "domain")), "html", null, true);
            echo "</b></p>
        ";
        }
        // line 42
        echo "    </div>
</td>";
    }

    public function getTemplateName()
    {
        return "AlphaLemonThemeEngineBundle:Themes:theme_panel_sections.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 40,  74 => 35,  53 => 32,  45 => 27,  38 => 24,  31 => 21,  28 => 20,  22 => 17,  20 => 16,  17 => 15,  170 => 44,  165 => 43,  160 => 39,  155 => 38,  151 => 37,  148 => 36,  139 => 34,  134 => 33,  132 => 32,  127 => 31,  123 => 30,  120 => 29,  111 => 27,  106 => 26,  103 => 42,  100 => 24,  92 => 37,  84 => 21,  76 => 20,  70 => 45,  68 => 44,  64 => 43,  57 => 34,  54 => 39,  51 => 31,  48 => 31,  46 => 24,  41 => 22,  33 => 20,  37 => 21,  34 => 22,  29 => 21,  26 => 19,  21 => 18,);
    }
}
