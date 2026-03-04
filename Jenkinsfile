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
                    if command -v php >/dev/null 2>&1 && command -v composer >/dev/null 2>&1; then
                      echo "Mode CI: php/composer locaux"
                    elif command -v docker >/dev/null 2>&1; then
                      echo "Mode CI: docker composer:2"
                    else
                      echo "Ni php/composer ni docker disponibles sur Jenkins"
                      exit 1
                    fi
                '''
            }
        }

        stage('Install Laravel dependencies') {
            steps {
                sh '''
                    set -e
                    if command -v composer >/dev/null 2>&1; then
                      composer install --no-interaction --prefer-dist
                    else
                      docker run --rm \
                        -u "$(id -u):$(id -g)" \
                        -v "$PWD":/app \
                        -w /app \
                        composer:2 \
                        composer install --no-interaction --prefer-dist
                    fi
                '''
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

                    if command -v php >/dev/null 2>&1; then
                      php artisan key:generate --force
                    else
                      docker run --rm \
                        -u "$(id -u):$(id -g)" \
                        -v "$PWD":/app \
                        -w /app \
                        composer:2 \
                        php artisan key:generate --force
                    fi
                '''
            }
        }

        stage('Migrate') {
            steps {
                sh '''
                    set -e
                    if command -v php >/dev/null 2>&1; then
                      php artisan migrate --force
                    else
                      docker run --rm \
                        -u "$(id -u):$(id -g)" \
                        -v "$PWD":/app \
                        -w /app \
                        composer:2 \
                        php artisan migrate --force
                    fi
                '''
            }
        }

        stage('Run tests') {
            steps {
                sh '''
                    set -e
                    if command -v php >/dev/null 2>&1; then
                      php artisan test
                    else
                      docker run --rm \
                        -u "$(id -u):$(id -g)" \
                        -v "$PWD":/app \
                        -w /app \
                        composer:2 \
                        php artisan test
                    fi
                '''
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
