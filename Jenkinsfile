pipeline {
    agent any

    environment {
        SLACK_CHANNEL = '#jenkins-slack-integration'
        SLACK_TOKEN_CREDENTIAL_ID = '5b65b72f-9ab0-409d-bd0d-84ec47b4d0e0'
        DOCKER_HUB_USERNAME = 'devsainar'
        DOCKER_HUB_CREDENTIALS_ID = 'devsainar-dockerhub'
        SONAR_SCANNER_HOME = tool name: 'SonarQube', type: 'hudson.plugins.sonar.SonarRunnerInstallation'
    }
    
    stages {
        stage('Building Docker Image') {
            steps {
                script {
                    echo 'Building Docker image...'
                    app = docker.build("${env.DOCKER_HUB_USERNAME}/php-mvc-blog:${env.BUILD_NUMBER}")
                }
            }
        }
        stage('Running Tests') {
            steps {
                script {
                    echo 'Running tests...'
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
                    withSonarQubeEnv('SonarScanner') {
                        sh "${env.SONAR_SCANNER_HOME}/bin/sonar-scanner"
                    }
                }
            }
        }
        stage('Pushing Image') {
            steps {
                script {
                    withCredentials([usernamePassword(credentialsId: "${env.DOCKER_HUB_CREDENTIALS_ID}", passwordVariable: 'DOCKERHUB_CREDENTIALS_PSW', usernameVariable: 'DOCKERHUB_CREDENTIALS_USR')]) {
                        echo 'Login to Docker Hub...'
                        sh 'echo $DOCKERHUB_CREDENTIALS_PSW | docker login -u $DOCKERHUB_CREDENTIALS_USR --password-stdin'
                        
                        echo 'Pushing image to registry...'
                        docker.withRegistry("https://registry.hub.docker.com") {
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
                    sh 'ssh -i ~/.ssh/authorized_keys sainar@192.168.56.102 "docker pull ${env.DOCKER_HUB_USERNAME}/php-mvc-blog:latest"'
                }
            }
        }
    }

    post {
        always {
            node {
                cleanWs()
                script {
                    sh "docker rmi ${env.DOCKER_HUB_USERNAME}/php-mvc-blog || true"
                }
            }
        }
        failure {
            node {
                echo 'Build failed!'
                slackSend (
                    color: 'red',
                    message: "Build ${env.JOB_NAME} ${env.BUILD_NUMBER} Failed! See console output at: (<${env.BUILD_URL}|Open>)",
                    tokenCredentialId: "${env.SLACK_TOKEN_CREDENTIAL_ID}"
                )
                error 'Pipeline aborted due to failure!'
            }
        }
        success {
            node {
                echo 'Build succeeded!'
                slackSend (
                    color: 'green',
                    message: "Build ${env.JOB_NAME} ${env.BUILD_NUMBER} Completed Successfully! See console output at: (<${env.BUILD_URL}|Open>)",
                    tokenCredentialId: "${env.SLACK_TOKEN_CREDENTIAL_ID}"
                )
            }
        }
    }
}
