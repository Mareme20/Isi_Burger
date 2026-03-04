pipeline {
    agent any

    options {
        timestamps()
        disableConcurrentBuilds()
    }

    parameters {
        string(name: 'REPO_URL', defaultValue: 'https://github.com/Mareme20/Isi_Burger.git', description: 'URL du repository GitHub')
        string(name: 'BRANCH_NAME', defaultValue: 'nom_prenom_burger', description: 'Branche a builder')
        string(name: 'DOCKER_IMAGE', defaultValue: 'isi-burger:jenkins', description: 'Nom de l image Docker')
    }

    environment {
        COMPOSER_ALLOW_SUPERUSER = '1'
    }

    stages {
        stage('Checkout (GitHub)') {
            steps {
                deleteDir()
                git branch: "${params.BRANCH_NAME}", url: "${params.REPO_URL}"
                sh 'git log -1 --oneline'
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
                    if [ -f .env.example ]; then
                      cp .env.example .env
                    else
                      touch .env
                    fi

                    php artisan key:generate --force

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
                '''
            }
        }

        stage('Migrate') {
            steps {
                sh 'php artisan migrate --force'
            }
        }

        stage('Build Docker image') {
            steps {
                sh 'docker build -t "${DOCKER_IMAGE}" .'
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
