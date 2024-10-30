<?php
class ObjectDeletedException extends Exception{}
class ObjectNotFoundException extends Exception{}
class SQLException extends Exception{}

class UserException extends Exception{}
class IncorrectPasswordException extends UserException{}
class InvalidEmailException extends UserException{}
