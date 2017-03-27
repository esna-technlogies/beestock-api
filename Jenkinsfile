pipeline {
    agent any

    stages {
        stage('Test') {
            steps {
                /** Preparing the docker machines for test **/
                dir('infrastructure/development/docker') {
                    sh 'ls -artls'
                }
                /** running the tests **/
                dir('service/application') {
                    sh 'composer install'
                    sh './vendor/bin/simple-phpunit'
                    sh './vendor/bin/behat'
                }
            }
        }
        stage('Build') {
            steps {
                echo 'Building..'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying....'
            }
        }
    }
}
