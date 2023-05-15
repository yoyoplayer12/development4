# development4

Conventional commits:

1. fix: a commit of the type fix patches a bug in your codebase (this correlates with PATCH in Semantic Versioning).
2. feat: a commit of the type feat introduces a new feature to the codebase (this correlates with MINOR in Semantic Versioning).
3. BREAKING CHANGE: a commit that has a footer BREAKING CHANGE:, or appends a ! after the type/scope, introduces a breaking API change (correlating with MAJOR in Semantic Versioning). A BREAKING CHANGE can be part of commits of any type.
4. types other than fix: and feat: are allowed, for example @commitlint/config-conventional (based on the Angular convention) recommends build:, chore:, ci:, docs:, style:, refactor:, perf:, test:, and others.
5. footers other than BREAKING CHANGE: <description> may be provided and follow a convention similar to git trailer format.



(tested with php 8.2):

Setup coding environment:
1. connect git 
2. add classes/config/config.ini
3. add keys in config
<!-- composer -->
4. php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
5. php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
6. php composer-setup.php
7. php -r "unlink('composer-setup.php');"
8. php composer.phar --version
9. php composer.phar init
10. enter untill no more questions
11. php composer.phar dump-autoload

<!-- sendgrid and cloudinary -->
12. composer require sendgrid/sendgrid
13. composer require cloudinary/cloudinary_php
14. composer require composer/ca-bundle


