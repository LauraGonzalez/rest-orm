# v0.5.2 (2015-10-14)

* Fix a bug when handling embedded HAL collections when the embedded rel is the same as a key on the parent

# v0.5.1 (2015-10-14)

* Fix handling of identifier values when it is private/protected

# v0.5.0 (2015-10-13)

* Allow configuring a custom repository class in the @Resource annotation

# v0.4.0 (2015-10-13)

* fixed cs
* Update hal-example.php to use the repository factory
* Add a factory for creating repositories
* Update RepositoryInterface with requirements arguments
* Pass requirements to the url generator from the repostiory
* Handle required url variables like /categories/{{categoryId}}/posts

# v0.3.0 (2015-10-09)

* Fix handling for embedded objects when using HAL
* Use the "Default" serializer group when serializing objects for persisting. This allows for adding groups to properties to exclude them from the PUT/POST requests. This is essentially the same functionality as the @ReadOnly jms/serializer annotation but for serializing not deserializing

# v0.2.0 (2015-10-05)

* Modify the Repository to accept parameters and pass them to the request factory
* Add missing argument to RepositoryInterface::save()
* Modify Request\Factory to pass parameters to the url generator
* Add missing method to UrlGeneratorInterface
* Add functionality to url generators to accept parameters and append a query string to the url
* Refactor Manager -> Repository
* fixed @return docblock

# v0.1.0 (2015-10-01)

* Initial release
