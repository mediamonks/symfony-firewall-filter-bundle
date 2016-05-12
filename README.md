# MediaMonks security bundle

This bundle adds to *Request* object information about current firewall.

Next to it **Guardian** listener was added, which provides some interesting functionality.

## Guardian

Aim of **Guardian** is to add 3 "hooks" related to login flow.

On compilation process it looks for all services tagged with *guardian.firewall_name*.

```
my_test_listener:
    class: Namespace\To\MyEventListener
    tags:
        - { name: 'guardian.admin'}

```

Base on what interfaces the service implements, handlers are added to proper hook: 
 * **MediaMonks\SecurityBundle\Security\LoginFlow\LoginAwareInterface** - interactive login to via firewall
 * **MediaMonks\SecurityBundle\Security\LoginFlow\CheckAwareInterface** - request via firewall
 * **MediaMonks\SecurityBundle\Security\LoginFlow\LogoutAwareInterface** - logout from firewall