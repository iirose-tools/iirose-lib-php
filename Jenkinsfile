pipeline {
    agent any
    
    stages {
        stage('Test') {
            steps {
                sh 'composer update'
                sh 'composer dump-autoload'
                sh 'phpunit'
                xunit thresholds: [
                    failed(
                        failureNewThreshold: '2',
                        failureThreshold: '3',
                        unstableNewThreshold: '1',
                        unstableThreshold: '1'
                    )
                ],
                tools: [
                    PHPUnit(
                        deleteOutputFiles: true,
                        failIfNotNew: true,
                        pattern: '',
                        skipNoTestFiles: true,
                        stopProcessingIfError: true
                    )
                ]
            }
        }
    }
    post{
        always{
            echo 'Post action'
        }

    }

}