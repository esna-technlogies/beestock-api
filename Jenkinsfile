pipeline {
    agent any

    stages {
        stage('Prepare') {
            steps {
                /** Preparing the docker machines for test **/

                dir('infrastructure/scripts/test') {
                    /** clean up of any previously running services **/

                    sh 'figlet -f standard "Preparation Process"'
                    sh '/bin/sh ./cleanup-docker-machines.sh || true'
                }

                dir('infrastructure/development/docker') {
                    /** Building new dockers **/

                    sh 'docker-compose up --build -d'
                }

                dir('infrastructure/scripts/test') {
                    /** Installing dependencies of user-service-php-fpm docker container **/

                    sh 'figlet -f standard "Installing dependencies"'
                    sh '/bin/sh ./install-service-dependencies.sh'
                }
            }
        }

        stage('Test') {
            steps {
                /** Running Tests **/
                dir('infrastructure/scripts/test') {
                    sh 'figlet -f standard "Running Tests"'

                    /** running the Unit tests **/
                    sh 'figlet -f bubble "Unit Tests"'
                    sh './run-unit-tests.sh'

                    /** running the Functional tests **/
                    sh 'figlet -f bubble "Functional tests"'
                    sh './run-functional-tests.sh'
                }
            }
        }

        stage('Build') {
            steps {
                dir('service/application') {
                    sh 'figlet -f standard "Building .."'
                }
            }
        }

        stage('Deploy') {
            steps {
                dir('service/application') {
                    sh 'figlet -f standard "Deploying .."'
                }
            }
        }

        stage('Clean up') {
            steps {
                dir('infrastructure/development/docker') {
                    sh 'figlet -f standard "Cleaning Up ..."'
                    sh 'docker-compose down'
                }

                dir('infrastructure/scripts/test') {
                    /** clean up of any previously running services **/

                    sh 'figlet -f standard "Preparation Process"'
                    sh './cleanup-docker-machines.sh || true'
                    sh 'cowsay -f ghostbusters Well done buddy !'
                }
            }
        }
    }
}
