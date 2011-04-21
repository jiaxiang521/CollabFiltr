<?php 

/**
 * The Evaluation provides means to evaluate a given recommender
 * using a sample of ratings
 */
interface Evaluation {
  /**
   * Gets the error value for the given recommender
   * @param Recommender $recommender the recommender to evaluate
   * @param array $sample a sample of ratings to evaluate the recommender against
   * @return double
   */
  public function calculate(Recommender $recommender, $sample);
}