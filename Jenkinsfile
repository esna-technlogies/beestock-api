pipeline {
    agent any

    stages {
        stage('Prepare') {
            steps {
                /** Preparing the docker machines for test **/
                /** clean up of any previously running services **/
                dir('infrastructure/development/docker') {
                    sh 'figlet -f standard "Preparation Process"'

                    sh 'docker-compose down || true'
                    sh 'docker rm -f $(docker ps -aq ) || true'
                    sh 'docker rmi -f $(docker images -aq) || true'
                    sh 'docker-compose up --build -d'
                }

                /** Installing dependencies of user-service-php-fpm docker container **/
                dir('service/application') {
                    sh 'figlet -f standard "Installing dependencies"'
                    sh 'docker exec -i user-service-php-fpm /bin/sh -c "composer install --no-progress"'
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
                    sh 'docker exec -i user-service-php-fpm /bin/sh -c "./vendor/bin/simple-phpunit"'
                }

                /** running the Functional tests **/
                dir('service/application') {
                    sh 'figlet -f bubble "Functional tests"'

                    /** setting mink base_url to run the functional tests **/
                    sh 'export BEHAT_PARAMS=\'{"extensions":{"Behat\\MinkExtension":{"base_url":"http://user-service.dev/app_test.php/"}}}\''
                    sh 'docker exec -i user-service-php-fpm /bin/sh -c "./vendor/bin/behat --profile default"'
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
                    sh 'docker rm -f $(docker ps -aq ) || true'
                    sh 'docker rmi -f $(docker images -aq) || true'
                    sh 'cowsay -f ghostbusters Well done buddy !'
                }
            }
        }
    }
}
