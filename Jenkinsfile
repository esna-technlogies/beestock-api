pipeline {
    agent any

    stages {
        stage('Test') {
            steps {
                /** Preparing the docker machines for test **/
                dir('infrastructure/development/docker') {
                    sh 'docker-compose up --build'
                }
                /** running the tests **/
                dir('service/application') {
                    sh 'composer install' 
                    sh './vendor/bin/simple-phpunit'
                    sh './vendor/bin/behat'
                }
                /** running the tests **/
                dir('service/application') {
                    sh 'docker-compose down'
                    sh 'docker rm -f $(docker ps -aq )'
                    sh 'docker rmi -f $(docker images -aq)'
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
