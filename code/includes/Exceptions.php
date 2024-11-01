<?php
class ObjectNotFoundException extends Exception{}
class SQLException extends Exception{}

class UserException extends Exception{}
class IncorrectPasswordException extends UserException{}
class InvalidEmailException extends UserException{}
class UserNotFoundException extends UserException{}

class ArticleException extends Exception{}
class ArticleNotFoundException extends ArticleException{}

class CommentException extends Exception{}
class CommentNotFoundException extends CommentException{}