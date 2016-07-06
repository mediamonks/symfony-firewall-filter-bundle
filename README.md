# MediaMonks firewall filter bundle

This bundle adds to *Request* object information about current firewall.

Next to it **FirewallFilter** listener was added, which provides an interesting functionality.

## FirewallFilter

Aim of **FirewallFilter** is to add 3 "hooks" related to login flow.

To enable FirewallFilter for certain firewall:

```
    security:
        firewall_foo:
            firewall_filter: ~
            
        firewall_bar:        
            firewall_filter:
                handler:
                    - bar_listener
```

Besides defining handlers in security configuration(**services ids**), developer can tag his services with *firewall_filter.firewall_name*.
They'll be processed and added to FirewallFilter via *CompilerPass* 

```
my_test_listener:
    class: Namespace\To\MyEventListener
    tags:
        - { name: 'firewall_filter.admin'}

```

Base on what interfaces the service implements, handlers are added to proper hook: 
 * **MediaMonks\SecurityBundle\Security\LoginFlow\LoginAwareInterface** - interactive login to firewall
 * **MediaMonks\SecurityBundle\Security\LoginFlow\CheckAwareInterface** - request via firewall
 * **MediaMonks\SecurityBundle\Security\LoginFlow\LogoutAwareInterface** - logout from firewall