pipeline {
    // agent {
    //     docker {
    //         image 'php:8.2-cli' // Use a PHP Docker image for running the tests
    //     }
    // }

    stages {
        stage('Checkout') {
            steps {
                // Checkout the code from version control
                git 'https://github.com/longhauler99/php-mvc-blog.git'
            }
        }
        // stage('Install Dependencies') {
        //     steps {
        //         // Install Composer
        //         sh 'curl -sS https://getcomposer.org/installer | php'
        //         sh 'php composer.phar install'
        //     }
        // }
        // stage('Run Tests') {
        //     steps {
        //         // Run PHPUnit tests
        //         sh 'vendor/bin/phpunit --configuration phpunit.xml'
        //     }
        // }
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
