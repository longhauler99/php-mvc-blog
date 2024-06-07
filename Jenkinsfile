pipeline {
    agent any
    
    stages {
        stage('Building Docker Image') {
            steps {
                script {
                    // Build the Docker image
                    def customImage = docker.build("php-mvc-blog:${env.BUILD_ID}")
                }
            }
        }
    stages {
        stage('Building Docker Image') {
            steps {
                script {
                    // Build the Docker image
                    def customImage = docker.build("php-mvc-blog:${env.BUILD_ID}")
                }
            }
        }
        stage('Running Tests') {
            steps {
                script {
                    // Use the built Docker image to run tests
                    def customImage = docker.image("php-mvc-blog:${env.BUILD_ID}")
                    customImage.inside('-u root') {
                        sh 'vendor/bin/phpunit --configuration phpunit.xml'
                    }
                }
            }
        }
        stage('SonarQube Vulnarability Analysis') {
            steps {
                script {
                    def scannerHome = tool 'SonarQube'
                    withSonarQubeEnv('SonarScanner') {
                        sh "${scannerHome}/sonar-scanner-4.8.1.3023/bin/sonar-scanner -X"
                    }
                }
            }
        }
    }

    post {
        always {
            // Clean up workspace
            cleanWs()
            
            // Remove the Docker image
            script {
                sh "docker rmi php-mvc-blog:${env.BUILD_ID} || true"
            }
        }
        success {
            // Notify on success
            slackSend channel: '#jenkins-slack-integration', color: 'green', message: 'Build  ${env.JOB_NAME} ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open>) Completed Successfully', tokenCredentialId: '5b65b72f-9ab0-409d-bd0d-84ec47b4d0e0'
            echo 'Build succeeded!'
        }
        failure {
            // Notify on failure
            echo 'Build failed!'
slackSend channel: '#jenkins-slack-integration', color: 'red', message: 'Build  ${env.JOB_NAME} ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open>) Failed', tokenCredentialId: '5b65b72f-9ab0-409d-bd0d-84ec47b4d0e0'        }
    }
}
