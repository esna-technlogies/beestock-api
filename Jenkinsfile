pipeline {
    agent any

    stages {
        stage('Prepare') {
            steps {
                /** Preparing the docker machines for test **/
                /** clean up of any previously running services **/
                dir('infrastructure/development/docker') {
                    sh 'figlet -f standard "Preparation Process"'

                    sh 'sudo docker-compose down || true'
                    sh 'sudo docker rm -f $(sudo docker ps -aq ) || true'
                    sh 'sudo docker rmi -f $(sudo docker images -aq) || true'
                    sh 'sudo docker-compose up --build -d'
                }

                /** Installing dependencies of symfony-PHP-fpm docker container **/
                dir('service/application') {
                    sh 'figlet -f standard "Installing dependencies"'
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "composer install --no-progress"'
                }
            }
        }

        stage('Test') {
            steps {
                /** Running Tests **/
                dir('service/application') {
                    sh 'figlet -f standard "Running Tests"'
                }

                /** running the Unit tests **/
                dir('service/application') {
                    sh 'figlet -f bubble "Unit Tests"'
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "./vendor/bin/simple-phpunit"'
                }

                /** running the Functional tests **/
                dir('service/application') {
                    sh 'figlet -f bubble "Functional tests"'
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "./vendor/bin/behat"'
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
                    sh 'sudo docker-compose down'
                    sh 'sudo docker rm -f $(sudo docker ps -aq ) || true'
                    sh 'sudo docker rmi -f $(sudo docker images -aq) || true'
                    sh 'cowsay -f ghostbusters Well done buddy !'
                }
            }
        }
    }
}
