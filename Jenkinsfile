pipeline {
    agent any

    environment {
        SLACK_CHANNEL = '#jenkins-slack-integration'
        SLACK_TOKEN_CREDENTIAL_ID = '5b65b72f-9ab0-409d-bd0d-84ec47b4d0e0'
        DOCKER_HUB_CREDENTIALS = credentials('jenkins-dockerhub')
    }
    
    stages {
            stage('Building Docker Image') {
                steps {
                    script {
                        echo 'Building Docker image...'
                        def customImage = docker.build("devsainar/php-mvc-blog:${env.BUILD_ID}")

                        echo 'Pushing image to repository...'
                        customImage.push("${env.DOCKER_HUB_CREDENTIALS}")
                    }
                }
            }
        stage('Running Tests') {
            steps {
                script {
                    echo 'Running tests...'
                    def customImage = docker.image("php-mvc-blog:${env.BUILD_ID}")
                    customImage.inside('-u root') {
                        sh 'vendor/bin/phpunit --configuration phpunit.xml'
                    }
                }
            }
        }
        stage('SonarQube Vulnerability Analysis') {
            steps {
                script {
                    echo 'Running SonarQube vulnerability analysis...'
                    def scannerHome = tool 'SonarQube'
                    withSonarQubeEnv('SonarScanner') {
                        sh "${scannerHome}/sonar-scanner-4.8.1.3023/bin/sonar-scanner"
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
        failure {
            // Stop the pipeline if any stage fails
            echo 'Build failed!'
            slackSend (
                color: 'red',
                message: "Build ${env.JOB_NAME} ${env.BUILD_NUMBER} Failed! (<${env.BUILD_URL}|Open>)",
                tokenCredentialId: "${env.SLACK_TOKEN_CREDENTIAL_ID}"
            )
            error 'Pipeline aborted due to failure!'
        }
        success {
            echo 'Build succeeded!'
            slackSend (
                color: 'green',
                message: "Build ${env.JOB_NAME} ${env.BUILD_NUMBER} Completed Successfully! (<${env.BUILD_URL}|Open>)",
                tokenCredentialId: "${env.SLACK_TOKEN_CREDENTIAL_ID}"
            )
        }
    }
}
