テスト
======

Yii2 アドバンスト・アプリケーションは Codeception を主たるテスト・フレームワークとして使用します。
`frontend`、`backend` および `common` の `tests` ディレクトリに、既にいくつかのサンプル・テストが用意されています。
下記の手順が動作するために、アプリケーションは `dev` 環境を使って初期化されているものとしています。
テストを `Production` 環境で実行する必要がある場合は、`yii_test` と `yii_test.bat` が
`environments/dev` からプロジェクトのルート・ディレクトリへ手作業でコピーされなければなりません。

テストには、テストを実行するたびに事前にクリーンアップされる **追加のデータベース** が必要になります。
このデータベースが、本番データベースにあるデータに対応するデータを保持することになります。
目的は、本番のデータに影響を与えることなく、データを保存するテストを実行することです。
本番データベースと同様の設定を使ってデータベース・サーバにデータベースを作成して、
可能な限り本番に近い環境でテストが走らせられるようにします。

   1. 本番データベースの接続文字列はおそらく `common/config/main-local.php` に書かれているでしょう。
      例えば `'dsn' => 'mysql:host=localhost;dbname=yii2advanced'` です。
      これを `common/config/test-local.php` にコピーして、テスト用データベースであることを明示するために `dbname` を変更します。
     `'dsn' => 'mysql:host=localhost;dbname=yii2advanced_test'`
   2. 空のデータベースを作成します。今の例では、(`common/config/test-local.php` の構成に従って)、
      MySql で `yii2advanced_test` を作ります。
   3. `./yii_test migrate` を実行します。
   4. テスト・スイートをビルドします : `./vendor/bin/codecept build`

これで、以下のコマンドを実行すれば、全てのサンプル・テストが開始されます。

```
vendor/bin/codecept run
```

次の図と同じような出力が得られる筈です。

![](images/tests.png)

あなたのテストを最新の状態に保つことが推奨されます。クラスまたは機能が削除されたときには、対応するテストも削除されるべきです。
テストは定期的に実行すべきです。あるいは、もっと良い方法として、継続インテグレーション・サーバをテストのためにセットアップしましょう。

Codeception をあなたのアプリケーションのために構成する方法を学ぶために [Yii2 Framework Case Study](http://codeception.com/for/yii) を参照して下さい。

### Common

共通クラスのためのテストは `common/tests` にあります。このテンプレートでは、単体テスト (`unit` テスト) しか有りません。
下記を実行してテストを実行します。

```
vendor/bin/codecept run -- -c common
```

`-c` オプションが `codeception.yml` 構成ファイルへのパスをセットすることを可能にしています。

単体テスト (`unit` テスト) スイート (`common/tests/unit` にあります) では、Yii フレームワークの機能、例えば、`Yii::$app`、アクティブレコード、フィクスチャなどを使用することが出来ます。
このことが可能なのは、テストの構成 `common/tests/unit.suite.yml` において `Yii2` モジュールが有効になっているからです。これを無効すると、テストを完全に独立した状態で走らせることが出来ます。


### Frontend

フロントエンドのテストは、単体テスト、機能テスト、受入テストを含んでいます。
下記を実行してテストを実行します。

```
vendor/bin/codecept run -- -c frontend
```

テスト・スイートの説明

* 単体 (`unit`) ⇒ フロントエンド・アプリケーションだけに関係する諸クラスのテスト
* 機能 (`functional`) ⇒ アプリケーションの内部的なリクエストとレスポンスのテスト (ウェブ・サーバ抜きで)
* 受入 (`acceptance`) ⇒ 実際のブラウザにおけるウェブ・アプリケーション、ユーザ・インタフェイス、javascript 相互作用のテスト

デフォルトでは、受入テストは無効になっています。受入テストを実行するためには、次のようにします。

#### 受入テストを実行する

受入テストは、デフォルトでは firefox のための [geckodriver](https://github.com/mozilla/geckodriver) を使用します。従って、[geckodriver](https://github.com/mozilla/geckodriver) が `PATH` に含まれていることを確認して下さい。.

受入テストを実行するためには、次のようにします。

1. `frontend/tests/acceptance.suite.yml.example` を `frontend/tests/acceptance.suite.yml` にリネームして、スイートの構成を有効にする

1. `composer.json` の `codeception/base` パッケージを `codeception/codeception` に置き換えて、
  全ての機能をもつ Codeception のバージョンをインストールする

1. Composer で依存を更新する

    ```
    composer update  
    ```

1. 受入テストのための新しいサポート・クラスを自動生成する

    ```
    vendor/bin/codecept build -- -c frontend
    ```

1. [Selenium Server](http://www.seleniumhq.org/download/) をダウンロードして起動する

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ``` 
    > 現在、geckodriver には Selenium との相互作用に [問題](https://github.com/facebook/php-webdriver/issues/492) があるため、
    > Selenium でプロトコル変換を有効にする必要があります。
    > `java -jar ~/selenium-server-standalone-x.xx.x.jar -enablePassThrough false`

1. ウェブ・サーバを開始する

    ```
    php -S 127.0.0.1:8080 -t frontend/web
    ```

1. これで全てのテストを実行することが出来る

   ```
   vendor/bin/codecept run acceptance -- -c frontend
   ```

## Backend

バックエンド・アプリケーションは、単体テストと機能テストのスイートを含んでいます。下記によって、テスト・スイートを実行します。

```
vendor/bin/codecept run -- -c backend
```
