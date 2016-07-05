# MediaMonks firewall filter bundle

This bundle adds to *Request* object information about current firewall.

Next to it **Guardian** listener was added, which provides some interesting functionality.

## Guardian

Aim of **Guardian** is to add 3 "hooks" related to login flow.

To enable Guardian for certain firewall:

```
    security:
        firewall_foo:
            guardian: ~
            
        firewall_bar:        
            guardian:
                handler:
                    - bar_listener
```

Besides defining handlers in security configuration(**services ids**), developer can tag his services with *guardian.firewall_name*.
They'll be processed and added to guardian flow via *CompilerPass* 

```
my_test_listener:
    class: Namespace\To\MyEventListener
    tags:
        - { name: 'guardian.admin'}

```

Base on what interfaces the service implements, handlers are added to proper hook: 
 * **MediaMonks\SecurityBundle\Security\LoginFlow\LoginAwareInterface** - interactive login to firewall
 * **MediaMonks\SecurityBundle\Security\LoginFlow\CheckAwareInterface** - request via firewall
 * **MediaMonks\SecurityBundle\Security\LoginFlow\LogoutAwareInterface** - logout from firewall