pipeline {
    agent any

    environment {
        SLACK_CHANNEL = '#jenkins-slack-integration'
        SLACK_TOKEN_CREDENTIAL_ID = '5b65b72f-9ab0-409d-bd0d-84ec47b4d0e0'
        DOCKER_HUB_USERNAME = 'devsainar'
        DOCKER_HUB_CREDENTIALS_ID = credentials('devsainar-dockerhub')
    }
    
    stages {
        stage('Building Docker Image') {
            steps {
                script {
                    echo 'Building Docker image...'
                    def app = docker.build("${env.DOCKER_HUB_USERNAME}/php-mvc-blog:${env.BUILD_NUMBER}")
                }
            }
        }
        stage('Running Tests') {
            steps {
                script {
                    echo 'Running tests...'
                    
                    def app = docker.image("${env.DOCKER_HUB_USERNAME}/php-mvc-blog:${env.BUILD_NUMBER}")

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
        stage('Login to Dockerhub') {
            steps {
                echo 'Login to Docker Hub...'
                withCredentials([usernamePassword(credentialsId: 'devsainar-dockerhub', passwordVariable: 'DOCKERHUB_CREDENTIALS_PSW', usernameVariable: 'DOCKERHUB_CREDENTIALS_USR')]) {
                    sh 'echo $DOCKERHUB_CREDENTIALS_PSW | docker login -u $DOCKERHUB_CREDENTIALS_USR --password-stdin'
                }
            }
        }
        stage('Pushing Image') {
            steps {
                script {
                    echo 'Pushing image to registry...'
                    sh "docker push ${env.DOCKER_HUB_USERNAME}/php-mvc-blog:${env.BUILD_NUMBER}" 
                    // app.push("${env.BUILD_NUMBER}")
                    // app.push("latest")
                }
            }    
        }
        stage('Deploying Image') {
            steps {
                script {
                    // Deploy Docker image to server via SSH using SSH key authentication
                    sh "ssh -i /var/jenkins_home/.ssh/id_rsa sainar@192.168.0.104 docker pull ${env.DOCKER_HUB_USERNAME}/php-mvc-blog:140"
                }
            }
        }
        stage('Run New Container') {
            steps {
                script {
                    // Deploy Docker image to server via SSH using SSH key authentication
                    sh "ssh -i /var/jenkins_home/.ssh/id_rsa sainar@192.168.0.104 docker run -p 9999:9999 ${env.DOCKER_HUB_USERNAME}/php-mvc-blog:140"
                }
            }
        }
    }

    post {
        always {
            sh 'docker logout'

            // Clean up workspace
            cleanWs()
            
            // Remove the Docker image
            script {
                sh "docker rmi ${env.DOCKER_HUB_USERNAME}/php-mvc-blog:${env.BUILD_NUMBER} || true"
            }
        }
        failure {
            // Stop the pipeline if any stage fails
            echo 'Build failed!'
            slackSend (
                color: 'red',
                message: "Build ${env.JOB_NAME} ${env.BUILD_NUMBER} Failed! See console output at: (<${env.BUILD_URL}|Open>)",
                tokenCredentialId: "${env.SLACK_TOKEN_CREDENTIAL_ID}"
            )
            error 'Pipeline aborted due to failure!'
        }
        success {
            echo 'Build succeeded!'
            slackSend (
                color: 'green',
                message: "Build ${env.JOB_NAME} ${env.BUILD_NUMBER} Completed Successfully! See console output at: (<${env.BUILD_URL}|Open>)",
                tokenCredentialId: "${env.SLACK_TOKEN_CREDENTIAL_ID}"
            )
        }
    }
}
