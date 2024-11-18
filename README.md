# Rese（飲食店予約アプリ）

飲食店予約アプリ  

![alt text](<スクリーンショット 2024-11-18 17.13.45.png>)

## 作成した目的

Webアプリ開発の勉強のため

## アプリケーション URL

- 開発(ローカル)環境  http://localhost
- phpMyAdmin：http://localhost:8080/
- Mailhog  http://localhost:8025/

## 機能一覧

- 会員登録機能
- ログイン機能
- ログアウト機能
- メール認証
- 検索機能
- 店舗詳細表示
- 予約
- マイページ
- 予約変更・キャンセル
- QRコード表示
- お気に入り登録・解除
- 管理者・店舗代表者のログイン
- 管理者・店舗代表者一覧表示
- 管理者・店舗代表者の登録
- お知らせメール送信
- 店舗情報の更新・作成
- 予約一覧表示
- 評価機能

## 仕様技術

- PHP7.4.9
- Laravel8.83.8
- MySQL8.0.26
- Nginx1.21.1
- Docker/Docker-compose

## テーブル設計

![alt text](<スクリーンショット 2024-11-19 8.28.21.png>)

## ER 図

![alt text](Rese.png)

## 環境構築

**Docker ビルド**

1. `git@github.com:Shinji-0323/Rese.git`
2. DockerDesktop アプリを立ち上げる
3. `docker-compose up -d --build`

**Laravel 環境構築**

1. `docker-compose exec php bash`
2. `composer install`
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.env ファイルを作成
4. .env に以下の環境変数を追加

```text
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

5. アプリケーションキーの作成

```bash
php artisan key:generate
```

6. マイグレーションの実行

```bash
php artisan migrate
```

7. シーディングの実行

```bash
php artisan db:seed
```

