# lms test
SECTION 1
     * Explain the role of hooks (actions and filters) in extending WooCommerce functionality.
        Answer: hooks are piece of code that allows you to modify or do certain action based in a event, manipulates variables get and pass data as you need to certain         part of your wordpress
        there are 2 types: 
        action: allows to put custom code in wordpress
        filter: allow to change and return data to certain action or event inside wordpress
    * Describe the potential performance implications of excessive custom fields on product pages in a large e-commerce site.
        Answer: will affect the speed of the site, cause for each product will be several queries related with the custom fields, also, theere will be a lot of data as         database fields that will create a lot of records on the wp-options and wp-post tables, so wlll be uneficient at certain point
SECTION 2
    The code is already there, should be inside wp-content/plugins/lms
    note this code should be in a folder call lms inside wp-plugins, i just set up the plugin code here so you need to create that folder
SECTION 3
    * Explain your approach to optimizing the plugin for a high-traffic e-commerce site (500,000+ monthly visitors, 50,000+ products).
        1) reduce the custom fields and custom code to the minimum
        2) optimize tables for indexes 
        3) review the custom code to adjust to good standards and slowness in duplicate code or loops issues (bad standards)
        4) apply a cache if possible and a redis database cache to reduce the excessive resources usage
    * Describe three potential security vulnerabilities specific to e-commerce plugins and how you would address them.
        1) exposure of data: review the page is not using any sensitive informacion and is not exposing the data in code or console or errors, solution will be to reviw code and check what need to be done to avoid this
        2) usage of URL data, should be fixed using POST info instead get, and validate server side any kind of info instead trusting the front end validation
        3) review the payment gateway proccess, specially the payment return page, and check is not allowing to get data from the browser, should validated with a enpoint based in a TID or tokenn
    * How would you ensure this plugin scales effectively as the product catalog and user base grow?
        1) the plugin have validations for data, using a good teqnique to prevent failures in the call of the endpont and ensuring data is there, also , is totally integrated with wordpress methods and avoiding to use external resources and any other vendor plugins, so shoudl works good for this, can be better, with more time and planning, but for a 2 hours solution, i think is good enough

