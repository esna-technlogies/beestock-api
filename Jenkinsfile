pipeline {
    agent any

    stages {
        stage('Test') {
            steps {
                /** Running tests **/
                dir('service/application') {
                    sh 'composer install || true'
                    sh './vendor/bin/simple-phpunit || true'
                    sh './vendor/bin/behat || true'
                }
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
