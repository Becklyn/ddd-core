3.0.0
=======

* All commands must extend AbstractCommand and call its constructor.
* All commands that are dispatched in response to events must be correlated with those events.
* All events that enter the application from external sources must have correlation and causation IDs. 