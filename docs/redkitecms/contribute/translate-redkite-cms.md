# Translate RedKite CMS into your language

This document will explain in detail how to translate RedKite CMS into another language.

RedKite CMS uses Symfony2's Translation component to manage the interface translation. Catalogues always live into a Block plugin's **Resources/translations** folder. 

So for example, the RedKite CMS application translations live under the [RedKite CMS Core plugin translations folder](https://github.com/redkite-labs/RedKiteCms/tree/master/lib/plugins/RedKiteCms/Core/RedKiteCms/Resources/translations), while the Link Block translation live under the [Link Block plugin translations folder](https://github.com/redkite-labs/RedKiteCms/tree/master/lib/plugins/RedKiteCms/Block/Link/Resources/translations).

Each catalogue is written as **xliff** file and its name follows the Symfony2 notation:

    RedKiteCmsBundle.[locale].xliff

## Translate RedKite CMS into your language

To start translating for RedKite CMS, simply copy the **translation xliff catalogue** and rename it with the locale you want to translate to. So if you want to translate RedKite CMS into Italian, you need to know the i18n Italian notation, which is **"it"** and rename the file as **[file name].it.xliff**.

Please bear in mind, the RedKite CMS default language is English and, while it is not mandatory, you should start a new translation from English.
    
Catalogues are written using xliff markup, which is a derivative of xml mark-up language. They are structured as follows:

.. code:: xml

    <?xml version="1.0"?>
    <xliff version="1.2" xmlns="urn:oasis:names:tc:xliff:document:1.2">
        <file source-language="en" datatype="plaintext" original="file.ext">
        <body>
            [ ... ]
            
            <trans-unit id="common_label_close">
                <source>common_label_close</source>
                <target>Close</target>
            </trans-unit>
            <trans-unit id="common_label_back">
                <source>common_label_back</source>
                <target>Back</target>
            </trans-unit>
            <trans-unit id="common_label_undo">
                <source>common_label_undo</source>
                <target>Undo</target>
            </trans-unit>
            
            [ ... ]       
        </body>
        </file>
    </xliff>

Each section of text is encapsulated into a **trans-unit** tag and this has two attributes: **source** and **target**.

The **source** attribute is a label that describes the real text and must not be translated. Yyou have to replace each target section with the translation of the message in the new language. See the sample below:

.. code:: xml

    <?xml version="1.0"?>
    <xliff version="1.2" xmlns="urn:oasis:names:tc:xliff:document:1.2">
        <file source-language="en" datatype="plaintext" original="file.ext">
        <body>
            [ ... ]
            
            <trans-unit id="common_label_close">
                <source>common_label_close</source>
                <target>Chiudi</target>
            </trans-unit>
            <trans-unit id="common_label_back">
                <source>common_label_back</source>
                <target>Indietro</target>
            </trans-unit>
            <trans-unit id="common_label_undo">
                <source>common_label_undo</source>
                <target>Annulla</target>
            </trans-unit>
            
            [ ... ]        
        </body>
        </file>
    </xliff>

Found a typo? Found something wrong in this documentation? [Just fork and edit it !](https://github.com/redkite-labs/RedKiteCms/edit/master/docs/contribute/translate-redkite-cms.md)