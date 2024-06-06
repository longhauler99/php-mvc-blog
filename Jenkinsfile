pipeline {
    agent {
        docker {
            image 'devsainar/php-mvc-blog:1.0' // Replace with your Docker Hub image and tag
            args '-u root' // Use root user to avoid permission issues (optional)
        }
    }
    stages {
        // stage('Install Dependencies') {
        //     steps {
        //         sh '''
        //         curl -sS https://getcomposer.org/installer | php
        //         php composer.phar install
        //         '''
        //     }
        // }
        stage('Run Tests') {
            steps {
                // Run PHPUnit tests
                sh 'vendor/bin/phpunit --configuration phpunit.xml'
            }
        }
    }

    post {
        always {
            // Clean up workspace
            cleanWs()
        }
        success {
            // Notify on success
            echo 'Build succeeded!'
        }
        failure {
            // Notify on failure
            echo 'Build failed!'
        }
    }
}
