pipeline {
    agent any

    stages {
        stage('Prepare for tests') {
            steps {
                /** Preparing the docker machines for test **/
                /** clean up of any previously running services **/
                dir('infrastructure/development/docker') {
                    sh 'sudo docker-compose down'
                    sh 'sudo docker rm -f $(sudo docker ps -aq )'
                    sh 'sudo docker rmi -f $(sudo docker images -aq)'
                    sh 'sudo docker-compose up --build -d'
                }
                /** running the tests **/
                dir('service/application') {
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "composer install --no-progress"'
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "./vendor/bin/simple-phpunit"'
                    sh 'sudo docker exec -i symfony-php-fpm /bin/sh -c "./vendor/bin/behat"'
                }
            }
        }
        stage('Test') {
            steps {
                /** running the tests **/
                dir('service/application') {
                    sh 'sudo docker-compose down'
                    sh 'sudo docker rm -f $(docker ps -aq )'
                    sh 'sudo docker rmi -f $(docker images -aq)'
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
