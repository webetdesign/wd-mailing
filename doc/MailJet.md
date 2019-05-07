MailJet
======

To use this bundle you must have a MailJet Account [Login](https://app.mailjet.com/signin)
This bundle is in link with the sonata user-bundle. You have to add the following field to tuhe user entity : 
    
       file : cms-skeleton/src/Application/Sonata/UserBundle/Entity/User.php
       
        /**
         * @var boolean|null
         */
        protected $emailing;
    
        /**
         * @var \DateTime
         */
        protected $emailingUpdatedAt;
    
        /*
         * @ORM\OneToOne(targetEntity="WebEtDesign\MailingBundle\Entity\MailingEmailing", mappedBy="user", cascade={"persist", "remove"}, fetch="EAGER")
         */
        private $mailingEmailing; 

After add this configuration for the relation : 
    
    file :  cms-skeleton/src/Application/Sonata/UserBundle/Resources/config/doctrine/User.orm.xml
    
    <one-to-one field="mailingEmailing" target-entity="WebEtDesign\MailingBundle\Entity\MailingEmailing" mapped-by="user" />
    
    <id name="id" column="id" type="integer">
        <generator strategy="AUTO" />
    </id>

    <field name="emailing" column="emailing" type="boolean" nullable="true" >
        <options>
            <option name="default">0</option>
        </options>
    </field>

    <field name="emailingUpdatedAt" column="emailingUpdatedAt" type="datetime" nullable="true" />
        

## Configuration

You have to create a file with the following configuration : 

    web_et_design_mailing:
      class:
        user: App\Application\Sonata\UserBundle\Entity\User
        media: App\Application\Sonata\MediaBundle\Entity\Media
      MailJet:
        PUBLIC_API_KEY: <YOUR_PUBLIC_API_KEY>
        SECRET_API_KEY: <YOUR_SECRET_API_KEY>
        
 You can find the keys on [your account](https://app.mailjet.com/account/api_keys) 
 
## Utilisation

The bundle provide you a block to show the statistics of your campaings. 
To use it on the dashboard page, add this on the sonata_admin.yaml file : 
    
    sonata_admin:
        dashboard:
            blocks:
            [...]
                -
                    class:    col-lg-12 col-md-6          
                    position: top                        
                    roles: [ROLE_ADMIN]
                    type:     mailing.admin.mailJet  
    sonata_block:
        blocks:
            [...]
            mailing.admin.mailJet:
                contexts: [admin] 

Don't forget to add the css and js files : 
    
    assets:
            extra_javascripts:
                [...]
                - bundles/webetdesigncms/cms_admin.js
                - bundles/webetdesignmailing/mailing_admin.js
            extra_stylesheets:
                [...]
                - bundles/webetdesigncms/cms_admin.css
                - bundles/webetdesignmailing/mailing_admin.css


## Subscription

You have access to a subscription form.
    
    {{ render(controller('WebEtDesignMailingBundle:MailJet/MailJetSub:sub')) }}

This form need :

   - Jquery (last version)
   - Bootstrap 4

