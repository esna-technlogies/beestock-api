pipeline {
    agent any

    stages {
        stage('Prepare for tests') {
            steps {
                /** Preparing the docker machines for test **/
                /** clean up of any previously running services **/
                dir('infrastructure/development/docker') {
                    sh 'sudo docker-compose down'
                    sh 'sudo docker rm -f $(sudo docker ps -aq ) || true'
                    sh 'sudo docker rmi -f $(sudo docker images -aq) || true'
                    sh 'sudo docker-compose up --build -d'
                }

                /** Installing dependencies of symfony-PHP-fpm docker container **/
                dir('/') {
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "composer install --no-progress"'
                }
            }
        }

        stage('Test') {
            steps {

                /** running the Unit tests **/
                dir('service/application') {
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "./vendor/bin/simple-phpunit"'
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "./vendor/bin/behat"'
                }

                /** running the Functional tests **/
                dir('service/application') {
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "composer install --no-progress"'
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "./vendor/bin/simple-phpunit"'
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "./vendor/bin/behat"'
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

        stage('Clean up') {
            steps {
                dir('infrastructure/development/docker') {
                    sh 'sudo docker-compose down'
                    sh 'sudo docker rm -f $(sudo docker ps -aq ) || true'
                    sh 'sudo docker rmi -f $(sudo docker images -aq) || true'
                }
            }
        }
    }
}
