# Implementation of LAMP Stack on Kubernetes Platform Using Laravel Application

Polish version README =>  [Wersja polska instrukcji](README.PL.md)
---

## Table of Contents
1. [Project Description](#Project-Description)
2. [Architecture](#Architecture)
3. [Configuration](#Configuration)
4. [Deployment](#Deployment)
5. [References](#References)

## Project Description
The application is based on part of the engineering project "[Web Application for Reserving and Paying for Parking Spaces](https://github.com/MAtt5816/diploma-project-parking-app)".

## Architecture
The application is built on the Laravel framework in PHP and uses a MySQL database. 

This project involved containerizing the aforementioned application in the LAMP stack and deploying it on the Kubernetes platform.

### Detailed list of manifests:
* *[Dockerfile](Dockerfile)* - description of the Docker image for the main application
* *[docker-compose.yml](docker-compose.yml)* - description of dependencies and project configuration for Docker Compose
* *[helm/values.yml](helm/values.yml)* - application environment variables used by HELM
* *[helm/secrets-example.yml](helm/secrets-example.yml)* - example of the *secrets.yml* file containing secrets used by HELM
* *[ingress.yaml](ingress.yaml)* - Ingress configuration

## Configuration
1. File [helm/values.yml](helm/values.yml):
   1. Set the **APP_URL** variable to match your domain name.
   2. If necessary, edit other environment variables.
2. File [helm/secrets.yml](helm/secrets.yml):
   1. Create *secrets.yml* based on the example [helm/secrets-example.yml](helm/secrets-example.yml).
   2. Set the database name, username, and passwords.
   3. Generate a new application key using the command below and enter it into the *secrets.yml* file.
   ```bash
   docker run --rm -v $(pwd):/app php:cli php /app/artisan key:generate
3.  File [ingress.yaml](ingress.yaml):
    1.  Set the host name (_host_) according to the value of **APP_URL** from the _helm/values.yml_ file.

## Deployment

### Prerequisites

-   Kubernetes or Minikube
-   Helm
-   Ingress

### Launch

1.  Install the application via Helm:
```bash
helm install laravel-kubernetes -f helm/values.yml -f helm/secrets.yml stable/lamp
```
2.  Start Ingress:

```bash
kubectl apply -f ingress.yaml
```
If using Minikube, open a new terminal and start the Minikube tunnel:
```bash
minikube tunnel
```
3.  If running locally, add the hostname and Kubernetes/Minikube IP to your system `/etc/hosts` file.
4.  Wait for the services to start and open the application in your browser at the address defined in **APP_URL**.

## References
-   [Tutorial: How To Deploy Laravel 7 and MySQL on Kubernetes using Helm](https://www.digitalocean.com/community/tutorials/how-to-deploy-laravel-7-and-mysql-on-kubernetes-using-helm)
-   [Kubernetes: deploy Laravel the easy way](https://learnk8s.io/blog/kubernetes-deploy-laravel-the-easy-way)
