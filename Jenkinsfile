pipeline {
    agent any

    stages {
        stage('Clean Vendor Directory') {
            steps {
                sh 'rm -rf vendor'
            }
        }

        stage('Install Dependencies') {
            steps {
                sh 'composer update'
                sh 'composer install'
                sh 'ls -la vendor/bin'
            }
        }

        stage('Make PHPUnit Executable') {
            steps {
                sh 'chmod +x vendor/bin/phpunit'
            }
        }

        stage('Run Tests') {
            steps {
                sh 'vendor/bin/phpunit --configuration phpunit.xml'
            }
        }

        stage('Deploy') {
            steps{
                echo 'Deploying....'
            }
        }
    }
}