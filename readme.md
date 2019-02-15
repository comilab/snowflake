# snowflake

- 簡易CMSです
- 以下の機能があります:
  - 記事へのタグ付け、コメント、いいね
  - 記事を年/月/日/タグ別に一覧表示
  - 個別ページ管理
  - markdown記法を使用可能
  - ファイルアップロード
  - テンプレートファイルによるデザイン変更
  - RSS出力

## 動作環境
- Laravel5.7 および Vue2 を使用しているため、そちらの動作環境に準拠します

## インストール手順
1. /api/public 以下が表示されるように設置（パーミッション類は適宜変更してください）
2. /api/.env.example を /api/.envにコピーし、APP_ENVとDB周りの設定を調整
3. 以下を実行
```sh
$ cd api
$ php artisan key:generate
$ php artisan migrate --seed
$ php artisan storage:link
$ cd ../app
$ npm run build
```
管理画面は/adminにアクセスしてください
（初期ID: admin@example.com / PW: secret / 管理画面アクセス後に変更してください）

## 動作確認手順
1. 以下2つを並列実行
```sh
$ cd app
$ npm run dev
```
```sh
$ cd api 
$ php artisan serve
```
2. http://127.0.0.1:8000 にアクセス