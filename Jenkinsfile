pipeline {
    agent any

    stages {
        stage('Test') {
            steps {
                /** Preparing the docker machines for test **/
                dir('infrastructure/development/docker') {
                    sh 'sudo docker-compose up --build -d'
                }
                /** running the tests **/
                dir('service/application') {
                    sh 'sudo docker exec -it symfony-php-fpm /bin/sh -c "composer install --no-dev"'
                    sh 'sudo docker exec -it symfony-php-fpm /bin/sh -c "./vendor/bin/simple-phpunit"'
                    sh 'sudo docker exec -it symfony-php-fpm /bin/sh -c "./vendor/bin/behat"'
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
