pipeline {
    agent any

    environment {
        SLACK_CHANNEL = '#jenkins-slack-integration'
        SLACK_TOKEN_CREDENTIAL_ID = '5b65b72f-9ab0-409d-bd0d-84ec47b4d0e0'
        DOCKER_HUB_USERNAME = 'devsainar'
        DOCKER_HUB_CREDENTIALS = credentials('devsainar-dockerhub')
    }
    
    stages {
            stage('Building Docker Image') {
                steps {
                    script {
                        echo 'Building Docker image...'
                        def app = docker.build("${env.DOCKER_HUB_USERNAME}/php-mvc-blog")
                    }
                }
            }
        stage('Running Tests') {
            steps {
                script {
                    echo 'Running tests...'
                    
                    def app = docker.image("${env.DOCKER_HUB_USERNAME}/php-mvc-blog")

                    app.inside('-u root') {
                        sh 'vendor/bin/phpunit --configuration phpunit.xml'

                        sh 'echo "Tests passed"'
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
        stage('Pushing Image') {
            steps {
                script {
                    withCredentials([usernamePassword(credentialsId: "${env.DOCKER_HUB_CREDENTIALS}", passwordVariable: 'DOCKERHUB_CREDENTIALS_PSW', usernameVariable: 'DOCKERHUB_CREDENTIALS_USR')]) {
                        echo 'Login to Docker Hub...'
                        sh 'echo $DOCKERHUB_CREDENTIALS_PSW | docker login -u $DOCKERHUB_CREDENTIALS_USR --password-stdin'

                        echo 'Pushing image to registry...'
                        docker.withRegistry("https://registry.hub.docker.com") {
                            def app = docker.build("${env.DOCKER_HUB_USERNAME}/php-mvc-blog:${env.BUILD_NUMBER}")
                            app.push("${env.BUILD_NUMBER}")
                            app.push("latest")
                        }
                    }
                }
            }
        }
        stage('Deploying Image') {
            steps {
                script {
                    // Deploy Docker image to server via SSH using SSH key authentication
                    sh 'ssh -i ~/.ssh/authorized_keys sainar@192.168.56.102 "docker pull ${env.DOCKER_HUB_USERNAME}/php-mvc-blog:latest"'
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
                sh "docker rmi ${env.DOCKER_HUB_USERNAME}/php-mvc-blog || true"
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
