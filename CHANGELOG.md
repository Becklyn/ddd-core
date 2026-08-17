4.2.0
=======

* (fix) Replaced the legacy `@required` docblock annotations on `CommandHandler::setTransactionManager()` and `CommandHandler::setEventRegistry()` with the `#[Required]` attribute. Symfony removed docblock `@required` support in 7.0, so on Symfony 7 the setters were never called and every command handler failed with "Typed property ... must not be accessed before initialization".
* (improvement) Added `symfony/service-contracts` to `require`, which provides the `#[Required]` attribute.

4.1.0
=======

* (feature) Added support for Symfony 7.4 (raised PHP minimum to 8.2, added illuminate/collections ^10.0 and ^11.0 support).
* (feature) Updated PHPUnit to ^10.5 || ^11.0 and phpspec/prophecy-phpunit to ^2.2.

4.0.0
=======

* (bc) Separated CommandBus interface into a method which correlates and a method which does not correlate commands.

3.1.0
=======

* (feature) CommandBus can now correlate commands with a given message.

3.0.0
=======

* (bc) Added support for correlation and causation IDs on events and commands.
* (feature) Added support for illuminate/collections ^9.0

2.0.0
=======

* (feature) Upgraded library to use PHP 8.0 features which most notably brings return type improvements to identity classes.
* (bc) No longer supports PHP 7.

1.0.0
=======

* (feature) PHP7 branch for a set of framework-agnostic components for developing software with domain-driven design, event sourcing and CQRS (some assembly required).