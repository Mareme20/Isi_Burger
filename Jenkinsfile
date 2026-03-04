pipeline {
    agent any

    options {
        timestamps()
        disableConcurrentBuilds()
        skipDefaultCheckout(true)
    }

    parameters {
        string(name: 'REPO_URL', defaultValue: 'https://github.com/Mareme20/Isi_Burger.git', description: 'URL du repository GitHub')
        string(name: 'BRANCH_NAME', defaultValue: 'Marieme_Ndiaye_burger', description: 'Branche a builder')
        string(name: 'DOCKER_IMAGE', defaultValue: 'isi-burger:jenkins', description: 'Nom de l image Docker')
    }

    stages {

        stage('Checkout') {
            steps {
                deleteDir()
                git branch: "${params.BRANCH_NAME}", url: "${params.REPO_URL}"
                sh 'git log -1 --oneline'
            }
        }

        stage('Verify tools') {
            steps {
                sh '''
                    set -e
                    command -v git >/dev/null 2>&1 || { echo "git manquant sur Jenkins"; exit 1; }
                    command -v php >/dev/null 2>&1 || { echo "php manquant sur Jenkins"; exit 1; }
                    command -v composer >/dev/null 2>&1 || { echo "composer manquant sur Jenkins"; exit 1; }
                '''
            }
        }

        stage('Install Laravel dependencies') {
            steps {
                sh 'composer install --no-interaction --prefer-dist'
            }
        }

        stage('Prepare Laravel') {
            steps {
                sh '''
                    set -e

                    if [ -f .env.example ]; then
                      cp .env.example .env
                    else
                      touch .env
                    fi

                    mkdir -p database
                    touch database/database.sqlite

                    {
                      echo "DB_CONNECTION=sqlite"
                      echo "DB_DATABASE=$WORKSPACE/database/database.sqlite"
                      echo "CACHE_STORE=array"
                      echo "SESSION_DRIVER=array"
                      echo "QUEUE_CONNECTION=sync"
                      echo "MAIL_MAILER=log"
                    } >> .env

                    php artisan key:generate --force
                '''
            }
        }

        stage('Migrate') {
            steps {
                sh 'php artisan migrate --force'
            }
        }

        stage('Run tests') {
            steps {
                sh 'php artisan test'
            }
        }

        stage('Build Docker image') {
            steps {
                script {
                    if (sh(script: 'command -v docker >/dev/null 2>&1', returnStatus: true) == 0) {
                        sh "docker build -t '${params.DOCKER_IMAGE}' ."
                    } else {
                        currentBuild.result = 'UNSTABLE'
                        echo 'Docker CLI indisponible sur ce noeud Jenkins: build image ignore.'
                    }
                }
            }
        }
    }

    post {
        success {
            echo "Pipeline OK. Image Docker creee: ${params.DOCKER_IMAGE}"
        }
        failure {
            echo 'Pipeline en echec. Verifier les logs Jenkins.'
        }
    }
}
