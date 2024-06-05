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
                sh 'composer install --no-interaction'
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

        stage('Cleanup') {
            steps {
                echo 'Cleaning up..'
                // Clean up temporary files or resources
            }
        }

        stage('Deploy') {
            steps {
                echo 'Deploying....'
            }
        }
    }
}
