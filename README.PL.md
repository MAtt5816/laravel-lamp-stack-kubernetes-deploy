# Implementacja stack'u LAMP na platformie Kubernetes na przykładzie aplikacji Laravel

## Spis treści
1. [Opis projektu](#Opis-projektu)
2. [Architektura](#Architektura)
3. [Konfiguracja](#Konfiguracja)
4. [Wdrożenie](#Wdrożenie)
5. [Odniesienia](#Odniesienia)

## Opis projektu
Wdrożona została aplikacja bazująca na części projektu inżynierskiego  "[Aplikacja internetowa do rezerwowania i opłacania miejsc parkingowych](https://github.com/MAtt5816/diploma-project-parking-app)".

## Architektura
Aplikacja została zbudowana na szkielecie Laravel w języku PHP oraz korzysta z bazy danych MySQL.
Niniejszy projekt polegał na skonteneryzowaniu wyżej wspomnianiej aplikacji w stack'u LAMP oraz wdrożeniu jej na platformie Kubernetes.

### Szczegółowy wykaz manifestów:
* *[Dockerfile](Dockerfile)* - opis obrazu Docker głównej aplikacji
* *[docker-comopose.yml](docker-comopose.yml)* - opis zależności i konfiguracji projektu dla Docker Compose
* *[helm/values.yml](helm/values.yml)* - zmienne środowiskowe aplikacji wykorzystywane przez HELM
* *[helm/secrets-example.yml](helm/secrets-example.yml)* - przykład pliku *secrets.yml* zawierającego sekrety wykorzystywane przez HELM
* *[ingress.yaml](ingress.yaml) - konfiguracja Ingress

## Konfiguracja
1. Plik [helm/values.yml](helm/values.yml):
	1. Należy ustawić wartość zmiennej **APP_URL** zgodnie z nazwą Twojej domeny.
	2. W razie konieczności można edytować pozostałe zmienne środowiskowe.  
2. Plik [helm/secrets.yml](helm/secrets.yml):
	1. Należy utworzyć *secrets.yml* na bazie przykładu [helm/secrets-example.yml](helm/secrets-example.yml).
	2. Należy ustawić nazwę bazy danych, nazwę użytkownika i hasła.
	3. Wygeneruj nowy klucz aplikacji za pomocą poniższej komendy i wpisz go do pliku *secrets.yml*.
	 ```bash
	 docker run --rm -v $(pwd):/app php:cli php /app/artisan key:generate
	 ```
 3. Plik [ingress.yaml](ingress.yaml):
	 1. Należy ustawić nazwę hosta (*host*) zgodnie z wartością **APP_URL** z pliku *helm/values.yml*.

## Wdrożenie
### Wymagania wstępne
* Kubernetes lub Minikube
* Helm
* Ingress

### Uruchomienie
1. Zainstaluj aplikację poprzez Helm:
```bash
helm install laravel-kubernetes -f helm/values.yml -f helm/secrets.yml stable/lamp
```
2. Uruchom Ingress:
```bash
kubectl apply -f ingress.yaml
```
3. W przypadku użycia Minikube otwórz nowy termial i otwórz tunel Minikube:
```bash
minikube tunnel
```
4. W przypadku uruchomienia w środowisku lokalnym dodaj nazwę hosta i IP Kubernetes/Minikube do pliku systemowego `/etc/hosts`.
5. Zaczekaj na uruchomienie usług i otwórz aplikację w przeglądarce pod adresem zdefiniowanym w **APP_URL**.

## Odniesienia
* [Tutorial: How To Deploy Laravel 7 and MySQL on Kubernetes using Helm](https://www.digitalocean.com/community/tutorials/how-to-deploy-laravel-7-and-mysql-on-kubernetes-using-helm)
* [Kubernetes: deploy Laravel the easy way](https://learnk8s.io/blog/kubernetes-deploy-laravel-the-easy-way)
