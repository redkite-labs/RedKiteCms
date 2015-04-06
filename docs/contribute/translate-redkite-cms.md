# Translate RedKite CMS in your language

This document will explain in detail how to translate RedKite CMS within your language.

RedKite CMS uses Symfony2's Translation component to manage the interface translation. Catalogues always live into a Block plugin's **Resources/translations** folder, so, for example, the RedKite CMS application translations live under the [RedKite CMS Core plugin translations folder](https://github.com/redkite-labs/RedKiteCms/tree/master/lib/plugins/RedKiteCms/Core/RedKiteCms/Resources/translations), while the Link Block translation live under the [Link Block plugin translations folder](https://github.com/redkite-labs/RedKiteCms/tree/master/lib/plugins/RedKiteCms/Block/Link/Resources/translations).

Each catalogue is written as **xliff** file and its name follows the Symfony2 notation:

    RedKiteCmsBundle.[locale].xliff

## Translate RedKite CMS in your language

To start translating RedKite CMS, simply copy the **translation xliff catalogue** and rename it with the locale you want to translate, so, if you want to translate RedKite CMS in Italian, you need to know the i18n Italian notation, which is **"it"** and rename the file as **[file name].it.xliff**.

Keep in mind, RedKite CMS default language is English and, while it is not mandatory, you shouldstart a new translation from this one.
    
Catalogues are written using xliff markup, which a derivation of xml mark-up language. They are structured as follows:

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

Each text is encapsulated into a **trans-unit** tag and this one has two attributes: **source** and **target**.

The **source** attribute is a label that describes the real text and must not be translated, while you have to replace each target section with the translation of the message in your language. See the sample below:

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

Found a typo ? Something is wrong in this documentation ? [Just fork and edit it !](https://github.com/redkite-labs/RedKiteCms/edit/master/docs/contribute/translate-redkite-cms.md)