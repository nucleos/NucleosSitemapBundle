pipeline {
  agent {
    label 'php7.1'
  }

  stages {
    stage ('Checkout') {
      steps {
        checkout scm
      }
    }

    stage ('Build') {
      steps {
        sh 'composer update'
      }
    }

    stage ('Test') {
      steps {
        sh 'vendor/bin/phpunit -c phpunit.xml.dist --coverage-clover build/logs/clover.xml --log-junit build/logs/phpunit.xml'
        archive 'build/logs/*.xml'

        step([
            $class: 'XUnitBuilder', testTimeMargin: '3000', thresholdMode: 1,
            thresholds: [
                [$class: 'FailedThreshold', failureNewThreshold: '', failureThreshold: '0', unstableNewThreshold: '', unstableThreshold: ''],
                [$class: 'SkippedThreshold', failureNewThreshold: '', failureThreshold: '', unstableNewThreshold: '', unstableThreshold: '']
            ],
            tools: [[
                $class: 'PHPUnitJunitHudsonTestType',
                deleteOutputFiles: true,
                failIfNotNew: true,
                pattern: 'build/logs/phpunit.xml',
                skipNoTestFiles: false,
                stopProcessingIfError: true
            ]]
        ])

        step([
          $class: 'CloverPublisher',
          cloverReportDir: 'build/logs/',
          cloverReportFileName: 'clover.xml',
          healthyTarget: [methodCoverage: 70, conditionalCoverage: 80, statementCoverage: 80],
          unhealthyTarget: [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50],
          failingTarget: [methodCoverage: 0, conditionalCoverage: 0, statementCoverage: 0]
        ])
      }
    }

    /* Temporary disabled
    stage ('Deps') {
      steps {
        sh 'vendor/bin/composer-require-checker check composer.json'
      }
    }
    */

    stage ('Lint') {
      steps {
        sh 'vendor/bin/php-cs-fixer fix --verbose'
        sh 'composer validate'
      }
    }

    stage ('Analyse') {
      steps {
        sh 'vendor/bin/phpstan analyse -c phpstan.neon -l 7 src tests'
      }
    }
  }
}
