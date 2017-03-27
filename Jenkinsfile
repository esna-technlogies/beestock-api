pipeline {
    agent any

    stages {
        stage('Prepare') {
            steps {
                /** Prepare Test infrastructure / Docker Environments **/
                dir('service/application') {
                    sh 'composer install'
                    sh './vendor/bin/simple-phpunit'
                    sh './vendor/bin/behat'
                }
            }
        }
        stage('Test') {
            steps {
                /** Running tests **/
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
