    agent any
    stages {
        stage('Install PHP') {
            steps {
                sh '''
                if ! command -v php > /dev/null; then
                    echo "PHP not found, installing PHP..."
                    sudo apt-get update
                    sudo apt-get install -y php
                else
                    echo "PHP is already installed"
                fi
                '''
            }
        }
        stage('Install Dependencies') {
            steps {
                sh '''
                curl -sS https://getcomposer.org/installer | php
                php composer.phar install
                '''
            }
        }
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
