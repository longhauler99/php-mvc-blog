pipeline {
    agent {
        docker {
            image 'devsainar/php-mvc-blog:1.0'
            args '-u root' // Ensure root user to have permissions to execute necessary commands
        }
    }

    stages {
        stage('Clean Vendor Directory') {
            steps {
                sh 'rm -rf vendor'
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
