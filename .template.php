<!doctype>
<html>
    <head>
        <title>Math Questions</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <style>
        </style>
    </head>
    <body>

        <div class="bg-light p-5">
            <h1 class="text-center">Quick Math Problems</h1>
        </div>

        <div class="container p-5 m-auto" style="width:100%; max-width: 700px;">

            <?php if (!$app->user->isLoggedIn()) : ?>

                <form method="post">
                    <div class="form-group py-2">
                        <label>Password</label>
                    </div>
                    <div class="form-group py-2">
                        <input type="password" name="password" value="" class="form-control" />
                    </div>
                    <div class="form-group py-2">
                        <input type="submit" name="submit" value="Login" class="btn btn-primary" />
                    </div>
                </form>

            <?php else : ?>

                <div class="row">
                    <div class="col-12 text-center">
                        <h3>Next Question</h3>

                        <?php if ($app->getQuestion()) : ?>

                            <p>Question <?=($app->currentQuestionNumber() + 1)?> of <?=$app->maxQuestions()?></p>
                            <div style="max-width: 300px; width: 100%;" class="m-auto">
                                <form method="post">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-4 text-end pt-1">
                                                <input type="hidden" name="question" value="<?=$app->getQuestion()?>" />
                                                <labe class="form-label"><?=$app->getQuestion()?> =</label>
                                            </div>
                                            <div class="col-4">
                                                <input type="number" value="" name="answer" class="form-control" />
                                            </div>
                                            <div class="col-4 text-start">
                                                <input type="submit" name="submit" value="Submit" class="btn btn-primary" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        <?php else: ?>
                            <div class="alert alert-info">No more questions to answer for today!</div>
                        <?php endif ?>

                        <?php if (is_bool($app->getPreviousQuestionStatus())) : ?>
                            <div class="alert alert-<?=($app->getPreviousQuestionStatus() ? 'success' : 'danger')?>">
                                <?php if ($app->getPreviousQuestionStatus()) : ?>
                                    Correct!
                                <?php else : ?>
                                    Wrong.
                                <?php endif ?>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="col-12">
                        <hr />
                        <h3 class="text-center">Results</h3>

                        <?php foreach ($app->getResults() as $date => $results) : ?>
                            
                            <h5 class="text-center pt-4"><?=date("F jS", strtotime($date))?></h5>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th>Your Answer</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                        
                                    <?php foreach ($results as $result) : ?>
                                        <tr>
                                            <?php foreach ($result as $key => $value) : ?>
                                            <td>
                                                <?php
                                                    if ($key == 4) {
                                                        if ((string) $value === '1') {
                                                            echo 'Correct';
                                                        } else {
                                                            echo 'Wrong';
                                                        }
                                                    } else {
                                                        echo $value;
                                                    }
                                                ?>
                                            </td>
                                            <?php endforeach ?>
                                        </tr>
                                    <?php endforeach ?>

                                </tbody>
                            </table>

                        <?php endforeach ?>

                        <hr class="my-5" />

                        <p class="text-center"><a href="?logout=true" class="btn btn-sm btn-outline-secondary">Logout</a></p>

                    </div>
                </div>

            <?php endif ?>
        </div>
    </body>
</html>