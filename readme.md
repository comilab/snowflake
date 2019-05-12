# snowflake

- 簡易 CMS です
- 以下の機能があります:
  - 記事へのタグ付け、コメント、いいね
  - 記事を年/月/日/タグ別に一覧表示
  - 個別ページ管理
  - markdown 記法を使用可能
  - ファイルアップロード
  - テンプレートファイルによるデザイン変更
  - RSS 出力

## 動作環境

- Laravel5.7 および Vue2 を使用しているため、そちらの動作環境に準拠します

## インストール手順

1. /api/public 以下が表示されるように設置（パーミッション類は適宜変更してください）
2. /api/.env.example を /api/.env にコピーする
3. .env の APP_ENV を production にし、DB 周りなどの設定を調整
4. 以下を実行

```sh
$ cd api
$ composer install
$ php artisan key:generate
$ php artisan migrate
$ php artisan db:seed --class=UsersTableSeeder
$ php artisan db:seed --class=SettingsTableSeeder
$ php artisan storage:link
$ cd ../web
$ npm run build
```

管理画面は/admin にアクセスしてください
（初期 ID: admin@example.com / PW: secret / 管理画面アクセス後に変更してください）

## 動作確認手順（開発用）

1. 初回のみインストール手順と同様に.env を作成・編集（APP_ENV は local のまま）し、以下を実行

```sh
$ cd api
$ composer install
$ npm install
$ php artisan key:generate
# ダミー記事を50件作成します
$ php artisan migrate --seed
$ php artisan storage:link
$ cd ../web
$ npm install
```

2. 以下を実行

```sh
$ cd api
$ npm run dev:all
```

3. http://127.0.0.1:8000 にアクセス
