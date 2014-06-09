# Contributing

Thanks for your interest in contributing to the 2martens Web Platform (2WP).
There are several ways for contributing. In different stages of development
there are different kinds of contribution needed most.

Phases:

* [Definition Phase](CONTRIBUTING.md#definition-phase) (right now)
* [Planning Phase](CONTRIBUTING.md#planning-phase)
* [Implementation Phase](CONTRIBUTING.md#implementation-phase)
* [Testing Phase](CONTRIBUTING.md#testing-phase)
* [Release Phase](CONTRIBUTING.md#release-phase)
* [All Phases](CONTRIBUTING.md#all-phases)

## Definition phase
For detailed information on how to contribute head over to meta repo.

* support in defining the architecture of the 2WP
    * which bundles are in 2WP
    * what are the dependencies
    * what features are in what bundles
* support in defining the general abstract API
    * how does the package system work in detail
        * how is a package structured
        * how are package installation plugins structured
    * how to extend functionality of 2WP
        * how does the event system work
            * who is responsible
            * where will be events in any case
            * how are event names structured
        * how does bundle inheritance work in our context
    * what css classes will be used for what
        * how are the grid css classes named
    * what twig blocks are available in any case, what is their meaning
      and where can they be used

## Planning phase
For detailed information on how to contribute head over to the meta repo.

* support in determining 3rdParty dependencies
    * think about what is needed for each bundle
    * report needed JS dependencies (how exactly you find out in meta)
* support in designing the bundle API
    * what services will be offered
        * what can you do with them
        * what are they expecting
    * what configuration will be exposed
    * what is the database structure needed by this bundle
        * necessary to allow working on initial installation way ahead of
          finishing all parts
    * what (if any) of the configuration should be on global, per-project,
      both level(s)? (applies only to High-End API and Application bundles)
* support in writing the contribution part of documentation
    * this part is necessary for the implementation phase
    * it contains detailed information on how to contribute in that phase
        * issue policy

## Implementation phase
This phase implements the previously defined API. For more information take
a look at the [Symfony2 contribution documentation][1] and the contributing
section of the documentation.

* support in implementing bundles
    * implement outstanding issues (managed by issues in this repo)
    * fix bugs that occurred during testing (reference to issue)
    * write documentation for the bundle (head over to web-platform-docs
      for more on that)
    * perform code review and if you find issues, create an issue with
      description
* support in continuous integration
    * write build script for Jenkins server
    * download a build, install it with test data (provided) and test
      the user interface manually
    * note all issues down and report them following the guideline in documentation

## Testing phase
This phase is a dedicated testing phase aiming at stabilizing the builds
and fixing up all remaining and arising bugs.

* support in testing
    * report all issues
    * fix bugs
* support in release planning
    * assign issues to versions
    * lock builds down
    * deploy builds
    
## Release phase
This phase is the immediate time after releasing the first stable build.

* support in writing further documentation
    * HOW-TOs
    * best practices

## All phases
These tasks are important throughout the whole process.

* support in creating marketing material
    * presentations (.tex)
    * logos
* support in marketing
    * promote web platform when appropriate

[1]: http://symfony.com/doc/current/contributing/code/index.html
