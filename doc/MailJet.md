MailJet
======

To use this bundle you must have a MailJet Account [Login](https://app.mailjet.com/signin)

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


